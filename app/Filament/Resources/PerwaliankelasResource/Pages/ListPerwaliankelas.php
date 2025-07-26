<?php

namespace App\Filament\Resources\PerwaliankelasResource\Pages;

use App\Filament\Resources\PerwaliankelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerwaliankelas extends ListRecords
{
    protected static string $resource = PerwaliankelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
