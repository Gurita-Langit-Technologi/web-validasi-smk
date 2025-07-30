<?php

namespace App\Filament\Imports;

use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;


class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id_kelas')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('no_induk')
                ->label('No Induk')
                ->requiredMapping()
                ->rules(['required', 'max:12']),
            ImportColumn::make('nama_siswa')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

        ];
    }

    public function resolveRecord(): ?Siswa
    {
        return Siswa::firstOrNew([
            'id_siswa' => $this->data['id_siswa'],
        ]);
    }
    public function getJobBatchName(): ?string
    {
        return 'siswa-import';
    }




    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Data Siswa Sukses ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
