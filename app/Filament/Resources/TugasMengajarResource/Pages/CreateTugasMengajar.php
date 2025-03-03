<?php

namespace App\Filament\Resources\TugasMengajarResource\Pages;

use App\Filament\Resources\TugasMengajarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTugasMengajar extends CreateRecord
{
    protected static string $resource = TugasMengajarResource::class;
    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
