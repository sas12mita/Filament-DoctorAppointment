<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()->role=='doctor') {
            $user_id=Auth::user()->id;
            $doctor=Doctor::where('user_id',$user_id)->first();
            $data['doctor_id'] = $doctor->id;
        }
    
        $data['start_time'] = Carbon::parse($data['start_time'])->format('H:i');
        $data['end_time'] = Carbon::parse($data['end_time'])->format('H:i');
    
        if (Carbon::parse($data['end_time'])->lessThanOrEqualTo(Carbon::parse($data['start_time']))) {
            Notification::make()
                ->title("Earlier end time than start time")
                ->danger()
                ->send();
            throw ValidationException::withMessages([]);
        }
    
        $providedDate = Carbon::parse($data['date']);
        if ($providedDate->isBefore(Carbon::today())) {
            Notification::make()
                ->title("The schedule date cannot be before today")
                ->danger()
                ->send();
            throw ValidationException::withMessages([]);
        }
        $overlap = Schedule::where('doctor_id', $data['doctor_id'])
            ->where('date', $data['date'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                    });
            })
            ->exists();
    
        if ($overlap) {
            Notification::make()
                ->title("Schedule overlap")
                ->danger()
                ->send();
            throw ValidationException::withMessages([]);
        }
    
        return $data;
    }
}