<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $nama = 'SD Tahfizh SaQu UMA';
        $logoPath = null;

        try {
            if (Schema::hasTable('settings')) {
                $nama = Setting::where('key', 'nama_pesantren')->value('value') ?? $nama;
                $logoPath = Setting::where('key', 'logo_pesantren')->value('value');
            }
        } catch (\Exception $e) {}

        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;

        $brandHtml = $logoUrl 
            ? new \Illuminate\Support\HtmlString('<div style="display: flex; align-items: center; gap: 0.5rem;"><img src="' . $logoUrl . '" style="height: 2rem; width: auto; border-radius: 0.25rem;"><span>' . e($nama) . '</span></div>')
            : $nama;

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName($brandHtml)
            ->favicon($logoUrl)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
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
                \App\Http\Middleware\ApplyUserTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
