<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Task;
use Carbon\Carbon;

class SiswaChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Kegiatan 1 Semester';
    protected static string $color = 'info';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Data kegiatan per bulan
        $kegiatanPerBulan = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulanStart = $bulan->copy()->startOfMonth();
            $bulanEnd = $bulan->copy()->endOfMonth();

            $jumlahKegiatan = Task::whereBetween('start', [$bulanStart, $bulanEnd])->count();
            $kegiatanPerBulan[] = $jumlahKegiatan;
            $labels[] = $bulan->format('M Y');
        }

        // Data kegiatan per minggu (6 minggu terakhir)
        $kegiatanPerMinggu = [];
        $labelsMinggu = [];

        for ($i = 5; $i >= 0; $i--) {
            $minggu = Carbon::now()->subWeeks($i);
            $mingguStart = $minggu->copy()->startOfWeek();
            $mingguEnd = $minggu->copy()->endOfWeek();

            $jumlahKegiatan = Task::whereBetween('start', [$mingguStart, $mingguEnd])->count();
            $kegiatanPerMinggu[] = $jumlahKegiatan;
            $labelsMinggu[] = 'Minggu ' . (6 - $i);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kegiatan per Bulan',
                    'data' => $kegiatanPerBulan,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Kegiatan per Minggu',
                    'data' => $kegiatanPerMinggu,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Grafik Kegiatan 6 Bulan Terakhir',
                ],
            ],
        ];
    }
}
