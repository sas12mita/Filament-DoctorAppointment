<?php

namespace App\Filament\Resources\AppointemntResource\Pages;

use App\Filament\Resources\AppointemntResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointemnt extends EditRecord
{
    protected static string $resource = AppointemntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
