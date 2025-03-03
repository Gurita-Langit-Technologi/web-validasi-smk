<?php

namespace App\Filament\Resources\TugasMengajarResource\Pages;

use App\Filament\Resources\TugasMengajarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTugasMengajars extends ListRecords
{
    protected static string $resource = TugasMengajarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
