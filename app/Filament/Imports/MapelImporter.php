<?php

namespace App\Filament\Imports;

use App\Models\Mapel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class MapelImporter extends Importer
{
    protected static ?string $model = Mapel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_mapel')
                ->label('Kode Mapel')
                ->guess(['kode_mapel', 'kode', 'kode_diklat'])
                ->requiredMapping()
                ->rules(['required', 'max:20']),

            ImportColumn::make('nama_diklat')
                ->label('Nama Mata Pelajaran / Diklat')
                ->guess(['nama_diklat', 'nama_mapel', 'mapel', 'nama', 'mata_pelajaran'])
                ->requiredMapping()
                ->rules(['required', 'max:80']),

            ImportColumn::make('guru')
                ->relationship(name: 'guru', resolveUsing: ['nama_guru', 'kode_guru', 'id_guru'])
                ->label('Guru Pengampu')
                ->guess(['guru', 'nama_guru', 'kode_guru', 'id_guru', 'nip'])
                ->rules(['nullable']),
        ];
    }

    public function resolveRecord(): ?Mapel
    {
        //memperbarui produk jika sudah ada, dan membuat produk baru jika belum ada.
        return Mapel::firstOrNew([
            'kode_mapel' => $this->data['kode_mapel'],
        ]);
    }

    public function getJobBatchName(): ?string
    {
        return 'mapel-import';
    }

    public function getValidationMessages(): array
    {
        return [
            'kode_mapel.required' => 'Tidak boleh sama dan tidak boleh kosong.',
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Data Mata Pelajaran Telah Ditambahkan ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
