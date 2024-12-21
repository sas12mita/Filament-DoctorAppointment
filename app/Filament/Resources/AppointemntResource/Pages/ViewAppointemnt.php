<?php

namespace App\Filament\Resources\AppointemntResource\Pages;

use App\Filament\Resources\AppointemntResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAppointemnt extends ViewRecord
{
    protected static string $resource = AppointemntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
