<?php

namespace App\Filament\Resources\PatientReportResource\Pages;

use App\Filament\Resources\PatientReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatientReport extends EditRecord
{
    protected static string $resource = PatientReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
