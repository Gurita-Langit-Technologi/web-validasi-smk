<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Siswa;
use App\Models\Kelas;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Jumlah siswa terdaftar
        $totalSiswa = Siswa::count();

        // Statistik perbandingan jurusan
        $jurusanStats = Kelas::selectRaw('nama_kelas, COUNT(*) as jumlah')
            ->groupBy('nama_kelas')
            ->get();

        $jurusanTerbanyak = $jurusanStats->max('jumlah');
        $jurusanTerbanyakNama = $jurusanStats->where('jumlah', $jurusanTerbanyak)->first()?->nama_kelas ?? 'N/A';

        // Jam dan tanggal hari ini
        $waktuSekarang = Carbon::now()->format('H:i');
        $tanggalHariIni = Carbon::now()->format('d/m/Y');

        return [
            Stat::make('Siswa Terdaftar', number_format($totalSiswa))
                ->description('Total siswa aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Jurusan Terbanyak', $jurusanTerbanyakNama)
                ->description($jurusanTerbanyak . ' siswa')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
            Stat::make('Waktu Sekarang', $waktuSekarang)
                ->description($tanggalHariIni)
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
