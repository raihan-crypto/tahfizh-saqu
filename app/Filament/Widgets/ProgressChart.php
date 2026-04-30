<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProgressChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Hafalan Santri';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'half';
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'ustadz', 'guru']);
    }

    protected function getData(): array
    {
        $totals = \App\Models\Santri::select('total_hafalan_baris as total_baris')->get();

        $kategori = [
            '0-1 Juz' => 0,
            '2-5 Juz' => 0,
            '6-10 Juz' => 0,
            '11-20 Juz' => 0,
            '21-30 Juz' => 0,
        ];

        foreach ($totals as $row) {
            $juz = floor($row->total_baris / 300);

            if ($juz <= 1) {
                $kategori['0-1 Juz']++;
            } elseif ($juz <= 5) {
                $kategori['2-5 Juz']++;
            } elseif ($juz <= 10) {
                $kategori['6-10 Juz']++;
            } elseif ($juz <= 20) {
                $kategori['11-20 Juz']++;
            } else {
                $kategori['21-30 Juz']++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Santri',
                    'data' => array_values($kategori),
                    'backgroundColor' => [
                        '#ef4444',  // Red - 0-1 Juz
                        '#f59e0b',  // Amber - 2-5 Juz
                        '#10b981',  // Emerald - 6-10 Juz
                        '#3b82f6',  // Blue - 11-20 Juz
                        '#8b5cf6',  // Purple - 21-30 Juz
                    ],
                    'borderWidth' => 3,
                    'borderColor' => '#ffffff',
                    'hoverOffset' => 12,
                ],
            ],
            'labels' => array_keys($kategori),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}