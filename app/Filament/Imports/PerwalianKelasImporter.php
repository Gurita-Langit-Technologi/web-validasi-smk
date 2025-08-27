<?php

namespace App\Filament\Imports;

use App\Models\PerwalianKelas;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class PerwalianKelasImporter extends Importer
{
    protected static ?string $model = PerwalianKelas::class;

    public static function getColumns(): array
    {
        return [

            ImportColumn::make('nama_guru') //ini harus disesuaikan dengan kolom d csv, wes itu kunci kesalahan setengah hari ini selain harus menggunakan relation
                ->relationship(
                    name: 'guru', // relasi di model TugasMengajar
                    resolveUsing: ['nama_guru', 'kode_guru'] // cari guru berdasarkan nama atau kode
                ),

            ImportColumn::make('nama_kelas') //ini harus disesuaikan dengan kolom d csv, wes itu kunci kesalahan setengah hari ini selain harus menggunakan relation
                ->relationship(
                    name: 'kelas',
                    resolveUsing: ['kode_kelas', 'nama_kelas'] // cari berdasarkan nama atau kode
                )


        ];
    }

    public function resolveRecord(): ?PerwalianKelas
    {


        return new PerwalianKelas();
    }

    public function getJobBatchName(): ?string
    {
        return 'perwalian-kelas-import';
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Perwalian Kelas berhasil diimpor: '
            . number_format($import->successful_rows) . ' '
            . str('baris')->plural($import->successful_rows) . ' berhasil.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('baris')->plural($failedRowsCount) . ' gagal diimpor.';

            // Tambahkan detail error langsung di notifikasi
            $body .= "\n\nDetail error:\n";
            foreach ($import->getFailedRows() as $index => $row) {
                $rowNumber = $index + 1; // Nomor baris
                $errors = implode(', ', $row->errors ?? []);
                $dataPreview = implode(' | ', $row->data ?? []);

                $body .= "Baris {$rowNumber}: {$dataPreview}\n";
                $body .= "Error: {$errors}\n\n";
            }
        }

        return $body;
    }
}
