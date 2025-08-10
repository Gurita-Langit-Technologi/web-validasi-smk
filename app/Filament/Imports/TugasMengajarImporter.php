<?php

namespace App\Filament\Imports;

use App\Models\TugasMengajar;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

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
            ImportColumn::make('nama_guru')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kelas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_diklat')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

        ];
    }

    public function resolveRecord(): ?TugasMengajar
    {
        $guru = \App\Models\Guru::where('nama_guru', $this->data['nama_guru'])->first();
        $kelas = \App\Models\Kelas::where('nama_kelas', $this->data['kelas'])->first();
        $mapel = \App\Models\Mapel::where('nama_mapel', $this->data['nama_diklat'])->first();

        if (!$guru || !$kelas || !$mapel) {
            return null;
        }

        return TugasMengajar::firstOrNew([
            'id_guru' => $guru->id_guru,
            'id_kelas' => $kelas->id_kelas,
            'id_mapel' => $mapel->id_mapel,
        ]);
    }

    public function hydrateRecord(TugasMengajar $record): TugasMengajar
    {
        $guru = \App\Models\Guru::where('nama_guru', $this->data['nama_guru'])->first();
        $kelas = \App\Models\Kelas::where('nama_kelas', $this->data['kelas'])->first();
        $mapel = \App\Models\Mapel::where('nama_mapel', $this->data['nama_diklat'])->first();

        $record->id_guru = $guru->id_guru;
        $record->id_kelas = $kelas->id_kelas;
        $record->id_mapel = $mapel->id_mapel;

        // Jika ada kolom kompetensi_keahlian di tabel tugas_mengajar,
        // tambahkan assign disini:
        // $record->kompetensi_keahlian = $this->data['kompetensi_keahlian'];

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Tugas mengajar berhasil ditambahkan ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    public function getJobBatchName(): ?string
    {
        return 'tugas-mengajar-import';
    }
}
