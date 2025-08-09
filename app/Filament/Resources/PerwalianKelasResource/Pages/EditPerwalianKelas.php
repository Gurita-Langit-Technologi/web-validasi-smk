<?php

namespace App\Filament\Resources\PerwalianKelasResource\Pages;

use App\Filament\Resources\PerwalianKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerwalianKelas extends EditRecord
{
    protected static string $resource = PerwalianKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
