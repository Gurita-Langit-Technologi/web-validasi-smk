<?php

namespace App\Filament\Imports;

use App\Models\WaliKelas;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class WaliKelasImporter extends Importer
{
    protected static ?string $model = WaliKelas::class;

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

            ImportColumn::make('kode_wali')
                ->label('Kode Wali')
                ->guess(['kode_wali', 'kode'])
                ->rules(['nullable', 'max:20']),

            ImportColumn::make('nama_wali')
                ->label('Nama Wali')
                ->guess(['nama_wali', 'nama'])
                ->rules(['nullable', 'max:80']),

            ImportColumn::make('role')
                ->label('Role')
                ->guess(['role', 'peran'])
                ->rules(['nullable', 'max:30']),

            ImportColumn::make('password')
                ->label('Password')
                ->guess(['password'])
                ->rules(['nullable', 'max:100']),
        ];
    }

    public function beforeSave(): void
    {
        if (blank($this->record->kode_wali)) {
            $this->record->kode_wali = $this->record->guru?->kode_guru ?? 'WALI';
        }

        if (blank($this->record->nama_wali)) {
            $this->record->nama_wali = $this->record->guru?->nama_guru ?? 'Wali Kelas';
        }

        if (blank($this->record->role)) {
            $this->record->role = 'wali kelas';
        }

        if (blank($this->record->password)) {
            $this->record->password = \Illuminate\Support\Facades\Hash::make('12345678');
        } elseif (! str_starts_with((string) $this->record->password, '$2y$')) {
            $this->record->password = \Illuminate\Support\Facades\Hash::make($this->record->password);
        }
    }

    public function resolveRecord(): ?WaliKelas
    {
        return new WaliKelas();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your wali kelas import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
