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
    protected function getStats(): array
    {
        return [
            Stat::make('Total Santri', Santri::count())
                ->description('Jumlah santri aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Total Ustadz', Ustadz::count())
                ->description('Jumlah ustadz pengampu')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([1, 2, 4, 5, 8, 8, 10]),
            Stat::make('Total Kelas Halaqah', Santri::distinct('kelas_halaqah')->count('kelas_halaqah'))
                ->description('Halaqah aktif')
                ->descriptionIcon('heroicon-m-bookmark-square')
                ->color('warning')
                ->chart([2, 5, 6, 8, 7, 9, 11]),
            Stat::make('Total Baris Setoran', Setoran::sum('ziyadah_baris') + Setoran::sum('rabth_baris') + Setoran::sum('murajaah_baris'))
                ->description('Pencapaian baris keseluruhan')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('danger')
                ->chart([15, 20, 25, 40, 50, 70, 100]),
        ];
    }
}