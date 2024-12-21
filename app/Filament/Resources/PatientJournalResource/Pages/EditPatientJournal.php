<?php

namespace App\Filament\Resources\PatientJournalResource\Pages;

use App\Filament\Resources\PatientJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatientJournal extends EditRecord
{
    protected static string $resource = PatientJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
