<?php

namespace App\Filament\Resources\PatientJournalResource\Pages;

use App\Filament\Resources\PatientJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePatientJournal extends CreateRecord
{
    protected static string $resource = PatientJournalResource::class;
}
