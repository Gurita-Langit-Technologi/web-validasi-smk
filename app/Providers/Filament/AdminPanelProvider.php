<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\GuruResource;
use App\Filament\Resources\SiswaResource;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Resources\TugasMengajarResource;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => '#E50046',
                'info' => Color::Blue,
                'primary' => '#fa8072',
                'success' => '#123524',
                'warning' => Color::Orange,
                'text1' => '#02006c',
                'text2' => Color::Green,
                'text3' => '#00879E',
                'text4' => '#A04747',
                'text5' => '#605678',


            ])
            ->font('Poppins')
            ->favicon(url: '/images/fic.png')
            ->brandName('SMK-PGRI 1')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            // ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                Widgets\StatsOverviewWidget::class,

            ])
            ->navigationItems([
                NavigationItem::make('Guru Baru')
                    ->url(fn() => GuruResource::getUrl('create'))
                    ->icon('heroicon-o-plus')
                    ->sort(1),
            ])
            ->navigationItems([
                NavigationItem::make('Siswa Baru')
                    ->url(fn() => SiswaResource::getUrl('create'))
                    ->icon('heroicon-o-plus')
                    ->sort(2),
            ])
            ->navigationItems([
                NavigationItem::make('Tugas Mengajar Baru')
                    ->url(fn() => TugasMengajarResource::getUrl('create'))
                    ->icon('heroicon-o-plus')
                    ->sort(3),
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
