<?php

namespace App\Filament\Resources\AppointemntResource\Pages;

use App\Filament\Resources\AppointemntResource;
use App\Models\Appointment;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListAppointemnts extends ListRecords
{
    protected static string $resource = AppointemntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'patient') {
            return Appointment::query()
                ->whereHas('patient.user', function ($query) use ($user) {
                    $query->where('id', $user->id);
                });
        } elseif ($user->role === 'doctor') {
            return Appointment::query()
                ->whereHas('doctor.user', function ($query) use ($user) {
                    $query->where('id', $user->id);
                });
        } elseif ($user->role === 'admin') {
            return Appointment::query();
        }
        return Appointment::query()->whereRaw('0 = 1'); // No records will match this
    }
}
