<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Resources\Pages\Page as PagesDashboard;

class Dashboard extends PagesDashboard
{
    protected static ?string $navigationIcon = 'ionicon-text-outline';

    protected static string $view = 'filament.pages.dashboard';
}
