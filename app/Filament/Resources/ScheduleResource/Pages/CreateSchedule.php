<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
    if (Carbon::parse($data['end_time'])->lessThanOrEqualTo(Carbon::parse($data['start_time']))) {
        throw ValidationException::withMessages([
            'end_time' => 'End time must be after the start time.',
        ]);
    }
    $providedDate = Carbon::parse($data['date']);
    if ($providedDate->isBefore(Carbon::today())) {
        throw ValidationException::withMessages([
            'date' => 'The schedule date cannot be before today.',
        ]);
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
            throw ValidationException::withMessages([
                'date' => 'The doctor already has a schedule that overlaps with this date and time.',
            ]);
        }
      
    
        // Return the data for Filament to process further if necessary
        return $data;
    
}
}