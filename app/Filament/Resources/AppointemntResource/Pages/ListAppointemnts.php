<?php

namespace App\Filament\Resources\AppointemntResource\Pages;

use App\Filament\Resources\AppointemntResource;
use App\Models\Appointment;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
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
    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->badge(fn() => $this->getCount())
                ->badgeColor('primary'),

            Tab::make('Pending')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'pending');
                })
                ->badge(fn() => $this->getCount('pending'))
                ->badgeColor('danger'),

            Tab::make('Booked')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'booked');
                })
                ->badge(fn() => $this->getCount('booked'))
                ->badgeColor('warning'),

            Tab::make('Completed')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'completed');
                })
                ->badge(fn() => $this->getCount('completed'))
                ->badgeColor('success'),

           

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

  
protected function getCount(string $status = null): int
    {
        $query = $this->getTableQuery();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }
    // protected function mutate
}
