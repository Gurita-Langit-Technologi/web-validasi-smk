<?php

namespace App\Filament\Resources\RekapKelasResource\Pages;

use App\Filament\Resources\RekapKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekapKelas extends EditRecord
{
    protected static string $resource = RekapKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
