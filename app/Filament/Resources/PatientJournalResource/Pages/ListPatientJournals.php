<?php

namespace App\Filament\Resources\PatientJournalResource\Pages;

use App\Filament\Resources\PatientJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatientJournals extends ListRecords
{
    protected static string $resource = PatientJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
