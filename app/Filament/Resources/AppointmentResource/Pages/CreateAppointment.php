<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        unset($data['specialization_id']);
        $doctorId = $data['doctor_id'];
        $appointmentDate = $data['appointment_date'];
        $startTime = \Carbon\Carbon::parse($data['start_time']);
        $endTime = \Carbon\Carbon::parse($data['end_time']);
        // Validate against the doctor's schedule
        $schedule = Schedule::where('doctor_id', $doctorId)
            ->where('date', $appointmentDate)
            ->first();
       
        // Ensure the appointment is within the doctor's schedule time
        $scheduleStart = \Carbon\Carbon::parse($schedule->start_time);
        $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time);

        if ($startTime < $scheduleStart || $endTime > $scheduleEnd) {
            Notification::make()
            ->title("The appointment time must be within the doctor\'s available schedule.")
            ->danger()
            ->send();
            throw ValidationException::withMessages([
                
            ]);
        }
        if (Carbon::parse($data['end_time'])->lessThanOrEqualTo(Carbon::parse($data['start_time']))) {
            Notification::make()
                ->title("Earlier end time than start time")
                ->danger()
                ->send();
            throw ValidationException::withMessages([]);
        }
        $overlap = Appointment::where('doctor_id', $doctorId)
        ->where('appointment_date', $appointmentDate)
        ->where(function ($query) use ($startTime, $endTime) {
            $query->where(function ($query) use ($startTime, $endTime) {
                // Case 1: Time ranges that don't cross midnight
                $query->where('start_time', '<=', $endTime)
                      ->where('end_time', '>=', $startTime);
            })->orWhere(function ($query) use ($startTime, $endTime) {
                // Case 2: Existing appointment crosses midnight
                $query->where('end_time', '<', 'start_time') // Crosses midnight
                      ->where(function ($query) use ($startTime, $endTime) {
                          $query->whereBetween($startTime, ['start_time', '23:59:59'])
                                ->orWhereBetween($endTime, ['00:00:00', 'end_time']);
                      });
            });
        })
        ->exists();
        if ($overlap) {
            Notification::make()
            ->title("The selected time slot overlaps with an existing appointment.")
            ->danger()
            ->send();
            throw ValidationException::withMessages([
            ]);
        }
        return $data;
    }
}
