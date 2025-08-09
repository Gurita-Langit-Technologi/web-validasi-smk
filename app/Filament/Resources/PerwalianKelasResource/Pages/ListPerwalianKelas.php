<?php

namespace App\Filament\Resources\PerwalianKelasResource\Pages;

use App\Filament\Resources\PerwalianKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerwalianKelas extends ListRecords
{
    protected static string $resource = PerwalianKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
