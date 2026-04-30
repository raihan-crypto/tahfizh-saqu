<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $slug = 'dashboard';
    protected static ?int $navigationSort = 0;

    public function mount(): void
    {
        // Redirect wali_santri ke dashboard mereka
        if (auth()->user()?->role === 'wali_santri') {
            $this->redirect('/app/wali-santri');
            return;
        }
    }

    public static function canAccess(): bool
    {
        // Allow access for all (wali_santri gets redirected in mount)
        return auth()->check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'guru', 'ustadz']);
    }
}
