<?php

namespace App\Filament\Resources\RekapKelasResource\Pages;

use App\Filament\Resources\RekapKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekapKelas extends ListRecords
{
    protected static string $resource = RekapKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
