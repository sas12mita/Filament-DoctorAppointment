<?php

namespace App\Filament\Resources\PatientReportResource\Pages;

use App\Filament\Resources\PatientReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatientReports extends ListRecords
{
    protected static string $resource = PatientReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
