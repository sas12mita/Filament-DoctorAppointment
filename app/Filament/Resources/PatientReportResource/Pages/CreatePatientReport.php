<?php

namespace App\Filament\Resources\PatientReportResource\Pages;

use App\Filament\Resources\PatientReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePatientReport extends CreateRecord
{
    protected static string $resource = PatientReportResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if (empty($data['file'])) {
            throw new \Exception('The file field is empty!');
        }

        return $data;
    }
}
