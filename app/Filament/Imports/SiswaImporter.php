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
            ImportColumn::make('kelas')
                ->relationship(name: 'kelas', resolveUsing: ['nama_kelas', 'kode_kelas', 'id_kelas'])
                ->label('Kelas / ID Kelas')
                ->guess(['id_kelas', 'kelas', 'nama_kelas', 'kode_kelas'])
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('no_induk')
                ->label('No Induk')
                ->guess(['no_induk', 'nis', 'nisn', 'nomor_induk'])
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('nama_siswa')
                ->label('Nama Siswa')
                ->guess(['nama_siswa', 'nama', 'nama_lengkap'])
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        $noInduk = $this->data['no_induk'] ?? '';

        // Tangani jika Excel mengekspor angka sebagai scientific notation (misal: 1,09E+08 atau 1.09E+08)
        if (stripos($noInduk, 'e') !== false) {
            $cleanNo = str_replace(',', '.', $noInduk);
            $noInduk = number_format((float) $cleanNo, 0, '', '');
        }

        return Siswa::firstOrNew([
            'no_induk' => $noInduk,
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
