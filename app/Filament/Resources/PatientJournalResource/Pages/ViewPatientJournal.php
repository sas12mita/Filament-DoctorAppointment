<?php

namespace App\Filament\Resources\PatientJournalResource\Pages;

use App\Filament\Resources\PatientJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPatientJournal extends ViewRecord
{
    protected static string $resource = PatientJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
