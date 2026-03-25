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
        $totals = DB::table('santris')
            ->leftJoin('setorans', 'setorans.santri_id', '=', 'santris.id')
            ->selectRaw('santris.id, COALESCE(SUM(setorans.ziyadah_baris), 0) as total_baris')
            ->groupBy('santris.id')
            ->get();

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
                    'backgroundColor' => ['#f87171', '#fbbf24', '#34d399', '#60a5fa', '#a78bfa'],
                ],
            ],
            'labels' => array_keys($kategori),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}