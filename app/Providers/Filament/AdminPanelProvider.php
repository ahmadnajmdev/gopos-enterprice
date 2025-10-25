<?php

namespace App\Providers\Filament;

use App\Filament\Pages\DashboardPage;
use App\Http\Middleware\lang;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Storage;
use URL;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('/')
            ->sidebarCollapsibleOnDesktop()
            // ->brandName(setting('general.brand_name'))
            // ->brandLogo(function () {
            //     $logopath = setting('general.logo');
            //     if (! empty($logopath))
            //     {
            //         return Storage::url($logopath);
            //     }
            //     return null;
            // })
            ->spa()
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_START, fn () => view('filament.components.navigation-filter'))
            ->brandLogoHeight('2.5rem')
            // ->darkModeBrandLogo(Storage::url(setting('general.logo')))
            ->unsavedChangesAlerts()

            ->plugins([
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled()
                    ->users([
                        'Admin' => 'test@admin.com',
                    ]),
            ])
            ->login()
            ->colors([
                'primary' => Color::Purple,
                'success' => Color::Green,
                'danger' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                DashboardPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')

            ->viteTheme('resources/css/filament/admin/theme.css')
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
                lang::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
