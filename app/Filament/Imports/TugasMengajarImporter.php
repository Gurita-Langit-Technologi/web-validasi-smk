<?php

namespace App\Filament\Imports;

use App\Models\TugasMengajar;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TugasMengajarImporter extends Importer
{
    protected static ?string $model = TugasMengajar::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id_guru')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('id_mapel')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('id_kelas')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('kode_guru')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_guru')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kelas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('mapel')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('jurusan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?TugasMengajar
    {


        return new TugasMengajar();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your tugas mengajar import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
