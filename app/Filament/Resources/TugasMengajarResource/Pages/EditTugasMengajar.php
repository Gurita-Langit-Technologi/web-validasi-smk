<?php

namespace App\Filament\Resources\TugasMengajarResource\Pages;

use App\Filament\Resources\TugasMengajarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTugasMengajar extends EditRecord
{
    protected static string $resource = TugasMengajarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
