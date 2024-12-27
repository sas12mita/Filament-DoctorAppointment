<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder; // Import Builder
use Illuminate\Support\Facades\Auth;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'patient') {
            // Admin or patient can view all schedules
            return Schedule::query(); // This is already a Builder
        }

        if ($user->role === 'doctor') {
            // Doctor can view only their own schedule by joining with doctors table
            return Schedule::whereHas('doctor', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }); // This is a Builder too
        }

        // Default to no records for other roles
        return Schedule::query()->whereRaw('1 = 0');
    }
}
