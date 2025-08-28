<?php

namespace App\Filament\Imports;

use App\Models\Guru;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class GuruImporter extends Importer
{
    protected static ?string $model = Guru::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_guru')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_guru')
                ->requiredMapping()
                ->rules(['required', 'max:80']),
        ];
    }

    public function resolveRecord(): ?Guru
    {
        //memperbarui produk jika sudah ada, dan membuat produk baru jika belum ada.
        return Guru::firstOrNew([
            'kode_guru' => $this->data['kode_guru'],
        ]);
    }

    //import job batch name

    public function getJobBatchName(): ?string
    {
        return 'guru-import';
    }

    public function getValidationMessages(): array
    {
        return [
            'kode_guru.required' => 'Tidak boleh sama dan tidak boleh kosong.',
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Data Guru Berhasil Ditambah' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
