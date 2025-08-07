<?php

namespace App\Filament\Pages;

//use Filament\Actions\Action;
//use Filament\Forms\Components\FileUpload;

class Dashboard extends \Filament\Pages\Dashboard
{
    // ...
    public function getColumns(): int | string | array
    {
        return [
            'md' => 4,
            'xl' => 5,
        ];
    }

    /*   protected function getHeaderActions(): array
    {
        return [
            Action::make('Import Data')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih file untuk diimpor')
                        ->acceptedFileTypes(['.csv', '.xlsx'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->importFile($data['file']);
                })
                ->modalHeading('Impor Data')
                ->modalSubmitActionLabel('Impor'),
        ];
    }
        */
}
