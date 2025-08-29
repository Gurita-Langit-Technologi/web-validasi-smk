<?php

namespace App\Filament\Resources\RekapKelasResource\Widgets;

use App\Models\RekapPengumpulan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RekapKelasStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $selesai = RekapPengumpulan::where('status', 'selesai')->count();
        $belum   = RekapPengumpulan::where('status', 'belum')->count();
        $proses  = RekapPengumpulan::where('status', 'proses')->count();

        return [
            Stat::make('Selesai', $selesai)
                ->description('Tugas yang sudah selesai')
                ->color('success'), // hijau

            Stat::make('Belum', $belum)
                ->description('Tugas yang belum dikerjakan')
                ->color('danger'), // merah

            Stat::make('Proses', $proses)
                ->description('Tugas yang sedang dikerjakan')
                ->color('warning'), // oranye
        ];
    }
}
