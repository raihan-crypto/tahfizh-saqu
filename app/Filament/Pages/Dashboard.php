<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $slug = 'dashboard';
    protected static ?int $navigationSort = 0;

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'guru', 'ustadz']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'guru', 'ustadz']);
    }
}
