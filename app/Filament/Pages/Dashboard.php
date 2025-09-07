<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SiswaChart;
use App\Filament\Widgets\CalendarWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getColumns(): int | string | array
    {
        return [
            'md' => 6,
            'xl' => 5,
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            SiswaChart::class,
            CalendarWidget::class,
        ];
    }
}
