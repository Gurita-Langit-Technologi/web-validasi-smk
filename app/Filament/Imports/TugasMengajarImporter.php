<?php

namespace App\Filament\Imports;

use App\Models\TugasMengajar;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class TugasMengajarImporter extends Importer
{
    protected static ?string $model = TugasMengajar::class;


    public static function getColumns(): array
    {
        return [
            ImportColumn::make('guru')
                ->relationship(name: 'guru', resolveUsing: ['nama_guru', 'kode_guru', 'id_guru'])
                ->label('Guru')
                ->guess(['guru', 'nama_guru', 'kode_guru', 'id_guru', 'nip'])
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('kelas')
                ->relationship(name: 'kelas', resolveUsing: ['nama_kelas', 'kode_kelas', 'id_kelas'])
                ->label('Kelas')
                ->guess(['kelas', 'nama_kelas', 'kode_kelas', 'id_kelas'])
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('mapel')
                ->relationship(name: 'mapel', resolveUsing: ['nama_diklat', 'kode_mapel', 'id_mapel'])
                ->label('Mata Pelajaran')
                ->guess(['mapel', 'nama_diklat', 'nama_mapel', 'kode_mapel', 'id_mapel', 'mata_pelajaran'])
                ->requiredMapping()
                ->rules(['required']),
        ];
    }
    public function resolveRecord(): ?TugasMengajar
    {
        // Selalu buat record baru, tidak update berdasarkan id
        return new TugasMengajar();
    }

    public function getJobBatchName(): ?string
    {
        return 'tugasmengajar-import';
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Data Tugas Mengajar berhasil ditambahkan ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
