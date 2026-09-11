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
            ImportColumn::make('guru')
                ->relationship(
                    name: 'guru',
                    resolveUsing: ['nama_guru', 'kode_guru', 'id_guru']
                )
                ->label('Guru')
                ->guess(['nama_guru', 'guru', 'kode_guru', 'id_guru', 'nip'])
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('kelas')
                ->relationship(
                    name: 'kelas',
                    resolveUsing: ['nama_kelas', 'kode_kelas', 'id_kelas']
                )
                ->label('Kelas')
                ->guess(['nama_kelas', 'kelas', 'kode_kelas', 'id_kelas'])
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?PerwalianKelas
    {
        $guruInput = $this->data['guru'] ?? $this->data['nama_guru'] ?? null;
        if ($guruInput) {
            $guru = \App\Models\Guru::where('nama_guru', $guruInput)
                ->orWhere('kode_guru', $guruInput)
                ->orWhere('id_guru', $guruInput)
                ->first();

            if ($guru) {
                return PerwalianKelas::firstOrNew(['id_guru' => $guru->id_guru]);
            }
        }

        return new PerwalianKelas();
    }

    public function beforeSave(): void
    {
        if (! $this->record->id_wali_kelas && $this->record->id_guru) {
            $wali = \App\Models\WaliKelas::where('id_guru', $this->record->id_guru)->first();
            if (! $wali) {
                $guru = \App\Models\Guru::find($this->record->id_guru);
                $wali = \App\Models\WaliKelas::create([
                    'id_guru' => $this->record->id_guru,
                    'id_kelas' => $this->record->id_kelas,
                    'kode_wali' => $guru?->kode_guru ?? 'WALI',
                    'nama_wali' => $guru?->nama_guru ?? 'Wali Kelas',
                    'role' => 'wali kelas',
                    'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                ]);
            }
            $this->record->id_wali_kelas = $wali->id_wali_kelas;
        }
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
