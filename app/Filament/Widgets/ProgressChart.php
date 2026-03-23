<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProgressChart extends ChartWidget
{
    protected ?string $heading = 'Presentase Target Pencapaian (Minimal 12 Baris)';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'half';

    protected function getData(): array
    {
        $totals = DB::table('santris')
            ->leftJoin('setorans', 'setorans.santri_id', '=', 'santris.id')
            ->selectRaw('santris.id, COALESCE(SUM(setorans.ziyadah_baris), 0) as total_baris')
            ->groupBy('santris.id')
            ->get();

        $sudahTercapai = $totals->filter(fn($s) => $s->total_baris >= 12)->count();
        $belumTercapai = $totals->count() - $sudahTercapai;

        return [
            'datasets' => [
                [
                    'label' => 'Target Santri',
                    'data' => [$sudahTercapai, $belumTercapai],
                    'backgroundColor' => ['#10b981', '#f43f5e'],
                ],
            ],
            'labels' => ['Sudah Tercapai', 'Belum Tercapai'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}