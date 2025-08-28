<?php

namespace App\Filament\Imports;

use App\Models\WaliKelas;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class WaliKelasImporter extends Importer
{
    protected static ?string $model = WaliKelas::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id_guru')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('id_kelas')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('kode_wali')
                ->requiredMapping()
                ->rules(['required', 'max:20']),

            ImportColumn::make('nama_wali')
                ->requiredMapping()
                ->rules(['required', 'max:80']),

            ImportColumn::make('role')
                ->requiredMapping()
                ->rules(['required', 'max:30']),

            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['required', 'max:30']),
        ];
    }

    public function resolveRecord(): ?WaliKelas
    {
        // return WaliKelas::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new WaliKelas();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your wali kelas import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
