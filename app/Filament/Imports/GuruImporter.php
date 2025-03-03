<?php

namespace App\Filament\Imports;

use App\Models\Guru;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;

class GuruImporter extends Importer
{
    protected static ?string $model = Guru::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_guru')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_guru')
                ->requiredMapping()
                ->rules(['required', 'max:80']),

        ];
    }


    public function resolveRecord(): ?Guru
    {
        dd($this->data);
        // return Guru::query()
        //     ->where('kode_guru', $this->data['kode_guru'])
        //     ->first();

        // $guru = Guru::query()
        //     ->where('kode_guru', $this->data['kode_guru'])
        //     ->first();

        // if (! $guru) {
        //     throw new RowImportFailedException("No product found with SKU [{$this->data['kode_guru']}].");
        // }

        // return $guru;
        return Guru::firstOrNew([
            'kode_guru' => $this->data['kode_guru'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your guru import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
