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
            ImportColumn::make('nisn')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_siswa')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kelas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('jurusan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        return Siswa::query()
            ->where('nisn', $this->data['nisn'])
            ->first();
        return new Siswa();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import Data Siswa Telah Berhasil Ditambahkan !! ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
