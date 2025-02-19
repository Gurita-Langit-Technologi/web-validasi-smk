<?php

namespace App\Filament\Resources\MapelResource\Pages;

use App\Filament\Resources\MapelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMapel extends CreateRecord
{
    protected static string $resource = MapelResource::class;
    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
