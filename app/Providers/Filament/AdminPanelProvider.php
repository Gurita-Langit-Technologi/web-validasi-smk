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
use App\Filament\Resources\MapelResource;
use App\Filament\Resources\KelasResource;
use App\Filament\Resources\SiswaResource;
use App\Filament\Resources\WalikelasResource;
use App\Filament\Resources\PerwalianKelasResource;
use App\Filament\Resources\RekapkelasResource;
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
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;



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

            ->plugin(
                FilamentFullCalendarPlugin::make()
                    ->schedulerLicenseKey('')
                    ->selectable(true)
                    ->editable(true)
                    ->timezone('Asia/Jakarta') // <- isi sesuai timezone kamu
                    ->locale('id') // <- lokal bahasa, misal 'id' untuk Indonesia
                    ->plugins([
                        'dayGrid',
                        'timeGrid',
                        'interaction',
                        'list',
                    ])
                    ->config([
                        'initialView' => 'dayGridMonth',
                        'headerToolbar' => [
                            'left' => 'prev,next today',
                            'center' => 'title',
                            'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                        ],
                    ])
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            // ->pages([\App\Filament\Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

                Widgets\StatsOverviewWidget::class,


            ])



            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->url(route('filament.admin.pages.dashboard')) // atau `Dashboard::getUrl()`
                            ->isActiveWhen(fn() => request()->routeIs('filament.admin.pages.dashboard')),
                    ])
                    ->groups([
                        NavigationGroup::make('Master Data')
                            ->items([
                                ...GuruResource::getNavigationItems(),
                                ...KelasResource::getNavigationItems(),
                                ...SiswaResource::getNavigationItems(),
                                ...MapelResource::getNavigationItems(),
                                ...RekapKelasResource::getNavigationItems(),
                                ...PerwalianKelasResource::getNavigationItems(),
                                ...WaliKelasResource::getNavigationItems(),
                                ...TugasMEngajarResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Tambah Data')
                            ->items([
                                NavigationItem::make('Guru Baru')
                                    ->url(fn() => GuruResource::getUrl('create'))
                                    ->icon('heroicon-o-user-plus')
                                    ->sort(1),
                                NavigationItem::make('Siswa Baru')
                                    ->url(fn() => SiswaResource::getUrl('create'))
                                    ->icon('heroicon-o-users')
                                    ->sort(2),
                                NavigationItem::make('Tugas Mengajar Baru')
                                    ->url(fn() => TugasMengajarResource::getUrl('create'))
                                    ->icon('heroicon-o-cursor-arrow-rays')
                                    ->sort(3),
                            ]),
                    ]);
            })




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
