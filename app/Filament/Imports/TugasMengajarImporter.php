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
            ImportColumn::make('kode_guru')
                ->requiredMapping()
                ->rules(['required', 'max:20', 'exists:guru,kode_guru']),

            ImportColumn::make('kode_kelas')
                ->requiredMapping()
                ->rules(['required', 'max:20', 'exists:kelas,kode_kelas']),

            ImportColumn::make('kode_mapel')
                ->requiredMapping()
                ->rules(['required', 'max:20', 'exists:mapel,kode_mapel']),
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
        $body = 'Data Wali kelas berhasil ditambahkan ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
