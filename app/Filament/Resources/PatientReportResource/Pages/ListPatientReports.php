<?php

namespace App\Filament\Resources\PatientReportResource\Pages;

use App\Filament\Resources\PatientReportResource;
use App\Models\PatientReport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListPatientReports extends ListRecords
{
    protected static string $resource = PatientReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): Builder
        {
    $user = Auth::user();

    if ($user->role === 'admin') {
        // Admin sees all reports
        return PatientReport::query();
    }

    if ($user->role === 'doctor') {
        // Doctor sees reports of their patients
        return PatientReport::whereHas('appointment', function ($query) use ($user) {
            $query->whereHas('doctor', function ($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id);
            });
        });
    }

    if ($user->role === 'patient') {
        // Patient sees only their own reports
        return PatientReport::whereHas('appointment', function ($query) use ($user) {
            $query->whereHas('patient', function ($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id);
            });
        });
    }

    // Default to no records if role is unknown
    return PatientReport::query()->whereRaw('1 = 0');
}

    }

