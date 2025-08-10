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
    /*
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_guru')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_guru')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kelas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('mata_diklat')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kompetensi_keahlian')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];

        //pada mapping ini semua perlu diisikan dataya pada csv, kalau kosong satu field tidak akan masuk. meskipun log di tabel semua sukses
    }

    public function resolveRecord(): ?TugasMengajar
    {
        // return TugasMengajar::firstOrNew([
        return TugasMengajar::firstOrNew([
            'kode_guru' => $this->data['kode_guru'],
        ]);
    }
    public function getJobBatchName(): ?string
    {
        return 'tugas-mengajar-import';
    }



    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Tugas mengajar berhasil ditambahkan' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    */

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama_guru') //ini harus disesuaikan dengan kolom d csv, wes itu kunci kesalahan setengah hari ini selain harus menggunakan relation
                ->relationship(
                    name: 'guru', // relasi di model TugasMengajar
                    resolveUsing: ['nama_guru', 'kode_guru'] // cari guru berdasarkan nama atau kode
                ),

            ImportColumn::make('nama_diklat') //ini harus disesuaikan dengan kolom d csv, wes itu kunci kesalahan setengah hari ini selain harus menggunakan relation
                ->relationship(
                    name: 'mapel',
                    resolveUsing: ['nama_diklat', 'kode_mapel'] // cari mapel berdasarkan nama atau kode
                ),

            ImportColumn::make('nama_kelas') //ini harus disesuaikan dengan kolom d csv, wes itu kunci kesalahan setengah hari ini selain harus menggunakan relation
                ->relationship(
                    name: 'kelas',
                    resolveUsing: ['nama_kelas', 'kode_kelas'] // cari kelas berdasarkan nama atau kode
                ),
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
        foreach ($import->getFailedRows() as $row) {
            Log::error('Row gagal: ', [
                'data' => $row->data,
                'errors' => $row->errors,
            ]);
        }

        $body = 'Tugas mengajar berhasil diimpor: '
            . number_format($import->successful_rows) . ' '
            . str('baris')->plural($import->successful_rows) . ' berhasil.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('baris')->plural($failedRowsCount) . ' gagal diimpor.';
        }

        return $body;
    }
}
