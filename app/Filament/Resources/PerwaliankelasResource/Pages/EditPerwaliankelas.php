<?php

namespace App\Filament\Resources\PerwaliankelasResource\Pages;

use App\Filament\Resources\PerwaliankelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerwaliankelas extends EditRecord
{
    protected static string $resource = PerwaliankelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
