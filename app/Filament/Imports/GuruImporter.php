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
                ->label('Kode Guru / NIP')
                ->guess(['kode_guru', 'kode', 'nip', 'nik'])
                ->requiredMapping()
                ->rules([
                    'required',
                    'max:255',
                ]),

            ImportColumn::make('nama_guru')
                ->label('Nama Guru')
                ->guess(['nama_guru', 'nama', 'nama_lengkap', 'guru'])
                ->requiredMapping()
                ->rules([
                    'required',
                    'max:80',
                ]),
        ];
    }

    public function resolveRecord(): ?Guru
    {
        $kodeGuru = $this->data['kode_guru'] ?? '';

        if (stripos($kodeGuru, 'e') !== false) {
            $cleanNo = str_replace(',', '.', $kodeGuru);
            $kodeGuru = number_format((float) $cleanNo, 0, '', '');
        }

        return Guru::firstOrNew([
            'kode_guru' => $kodeGuru,
        ]);
    }

    public function getJobBatchName(): ?string
    {
        return 'guru-import';
    }

    public function getValidationMessages(): array
    {
        return [
            'kode_guru.required' => 'Kode guru tidak boleh kosong.',
            'kode_guru.max' => 'Kode guru maksimal 255 karakter.',
            'nama_guru.required' => 'Nama guru tidak boleh kosong.',
            'nama_guru.max' => 'Nama guru maksimal 80 karakter.',
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import data guru selesai. ' .
            number_format($import->successful_rows) .
            ' ' .
            str('baris')->plural($import->successful_rows) .
            ' berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' .
                number_format($failedRowsCount) .
                ' ' .
                str('baris')->plural($failedRowsCount) .
                ' gagal diimport.';
        }

        return $body;
    }
}