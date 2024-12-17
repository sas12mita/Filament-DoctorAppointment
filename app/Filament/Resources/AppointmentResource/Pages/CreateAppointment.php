<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
    // Remove specialization_id before saving to the database
    unset($data['specialization_id']);
    return $data;
}
}
