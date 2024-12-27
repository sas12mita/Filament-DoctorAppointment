<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    // Add header actions like Create Action
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Tab-based filtering (Admin, Doctor, Patient)
    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->badge(fn() => $this->getCount())
                ->badgeColor('primary'),

            Tab::make('Admin')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('role', 'admin');
                })
                ->badge(fn() => $this->getCount('admin'))
                ->badgeColor('danger'),

            Tab::make('Doctor')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('role', 'doctor');
                })
                ->badge(fn() => $this->getCount('doctor'))
                ->badgeColor('warning'),

            Tab::make('Patient')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('role', 'patient');
                })
                ->badge(fn() => $this->getCount('patient'))
                ->badgeColor('success'),
        ];
    }

    // Adjust the query based on user roles (admin, doctor, patient)
    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        // Admin can see all users
        if ($user->role === 'admin') {
            return User::query();
        }

        // If role is not admin, then allow the user to see only their own profile
        return User::query()->where('id', $user->id);
    }

    // Get the count of users based on role
    protected function getCount(string $role = null): int
    {
        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        return $query->count();
    }
}
