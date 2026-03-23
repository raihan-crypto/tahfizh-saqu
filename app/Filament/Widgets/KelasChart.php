<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Widgets\ChartWidget;

class KelasChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Capaian Tahfizh per Kelas (Ziyadah Baris)';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'half';

    protected function getData(): array
    {
        $kelas = Santri::distinct()->pluck('kelas')->filter()->values()->toArray();
        $dataCapaian = [];

        foreach ($kelas as $k) {
            $total = \DB::table('setorans')
                ->join('santris', 'santris.id', '=', 'setorans.santri_id')
                ->where('santris.kelas', $k)
                ->sum('setorans.ziyadah_baris');
            $dataCapaian[] = (int)$total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Capaian Baris',
                    'data' => $dataCapaian,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $kelas,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}