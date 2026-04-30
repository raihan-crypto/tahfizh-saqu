<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use App\Models\Setoran;
use App\Models\Ustadz;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'ustadz', 'guru']);
    }
    protected function getStats(): array
    {
        $totalSetoran = \Illuminate\Support\Facades\Cache::remember('dashboard_total_setoran', 300, function () {
            return (int) \App\Models\Setoran::selectRaw('SUM(ziyadah_baris) + SUM(rabth_baris) + SUM(murajaah_baris) as total')->value('total');
        });

        $totalSantri = \Illuminate\Support\Facades\Cache::remember('dashboard_total_santri', 300, fn () => Santri::count());
        $totalUstadz = \Illuminate\Support\Facades\Cache::remember('dashboard_total_ustadz', 300, fn () => Ustadz::count());
        $totalKelas = \Illuminate\Support\Facades\Cache::remember('dashboard_total_kelas', 300, fn () => \App\Models\KelasHalaqah::count());

        return [
            Stat::make('Total Santri', $totalSantri)
                ->description('Jumlah santri aktif terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'class' => 'animate-fade-in-up stagger-1',
                ]),
            Stat::make('Total Ustadz', $totalUstadz)
                ->description('Ustadz pengampu halaqah')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([1, 2, 4, 5, 8, 8, 10])
                ->extraAttributes([
                    'class' => 'animate-fade-in-up stagger-2',
                ]),
            Stat::make('Total Kelas / Halaqah', $totalKelas)
                ->description('Halaqah aktif saat ini')
                ->descriptionIcon('heroicon-m-bookmark-square')
                ->color('warning')
                ->chart([2, 5, 6, 8, 7, 9, 11])
                ->extraAttributes([
                    'class' => 'animate-fade-in-up stagger-3',
                ]),
            Stat::make('Total Baris Setoran', number_format($totalSetoran))
                ->description('Pencapaian baris keseluruhan')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('danger')
                ->chart([15, 20, 25, 40, 50, 70, 100])
                ->extraAttributes([
                    'class' => 'animate-fade-in-up stagger-4',
                ]),
        ];
    }
}