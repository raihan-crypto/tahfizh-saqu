<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SetoranHarianChart extends ChartWidget
{
    protected ?string $heading = 'Statistik Setoran Harian (15 Hari Terakhir)';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'ustadz', 'guru']);
    }
    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $labels = [];
        $dataSabaq = [];
        $dataSabqi = [];
        $dataManzil = [];

        for ($i = 14; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');

            // Use Carbon date format for database queries
            $formattedDate = $date->format('Y-m-d');

            $totals = DB::table('setorans')
                ->whereDate('tanggal', $formattedDate)
                ->selectRaw('COALESCE(SUM(ziyadah_baris), 0) as sabaq, COALESCE(SUM(rabth_baris), 0) as sabqi, COALESCE(SUM(murajaah_baris), 0) as manzil')
                ->first();

            $dataSabaq[] = (int) data_get($totals, 'sabaq', 0);
            $dataSabqi[] = (int) data_get($totals, 'sabqi', 0);
            $dataManzil[] = (int) data_get($totals, 'manzil', 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sabaq (Baru)',
                    'data' => $dataSabaq,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)', // #10b981 with opacity
                    'borderColor' => '#10b981',
                    'fill' => true,
                ],
                [
                    'label' => 'Sabqi (Murojaah Dekat)',
                    'data' => $dataSabqi,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // #3b82f6 with opacity
                    'borderColor' => '#3b82f6',
                    'fill' => true,
                ],
                [
                    'label' => 'Manzil (Murojaah Jauh)',
                    'data' => $dataManzil,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)', // #f59e0b with opacity
                    'borderColor' => '#f59e0b',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'stacked' => true,
                    'min' => 0,
                    'suggestedMax' => 1600,
                    'ticks' => [
                        'stepSize' => 400,
                    ],
                ],
            ],
            'elements' => [
                'line' => [
                    'tension' => 0.4, // Make the line curved slightly for better look
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // A line chart with "fill: true" is an area chart in ChartJS
    }
}
