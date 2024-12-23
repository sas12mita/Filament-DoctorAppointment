<?php

namespace App\Filament\Resources\PatientReportResource\Pages;

use App\Filament\Resources\PatientReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPatientReport extends ViewRecord
{
    protected static string $resource = PatientReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
