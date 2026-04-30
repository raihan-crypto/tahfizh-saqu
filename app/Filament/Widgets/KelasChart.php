<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KelasChart extends ChartWidget
{
    protected ?string $heading = 'Rata-rata Capaian per Kelas (Juz)';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'half';
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'ustadz', 'guru']);
    }

    protected function getData(): array
    {
        $kelasHalaqahs = \App\Models\KelasHalaqah::all();
        
        $stats = \App\Models\Santri::select(
            'kelas_halaqah_id',
            DB::raw('COUNT(id) as total_santri'),
            DB::raw('SUM(total_hafalan_baris) as total_baris')
        )->groupBy('kelas_halaqah_id')->get()->keyBy('kelas_halaqah_id');

        $labels = [];
        $dataCapaian = [];

        foreach ($kelasHalaqahs as $k) {
            $stat = $stats->get($k->id);
            $santriDiKelas = $stat ? $stat->total_santri : 0;
            
            if ($santriDiKelas > 0) {
                $totalBaris = $stat->total_baris;
                $rataRataBaris = $totalBaris / $santriDiKelas;
                $rataRataJuz = round($rataRataBaris / 300, 1);
            } else {
                $rataRataJuz = 0;
            }
            
            $labels[] = $k->nama_kelas;
            $dataCapaian[] = $rataRataJuz;
        }

        // Generate vibrant gradient colors for each bar
        $colors = ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ef4444', '#06b6d4', '#f97316', '#ec4899', '#14b8a6', '#6366f1'];
        $bgColors = array_map(function($c, $i) use ($colors) {
            return $colors[$i % count($colors)];
        }, $dataCapaian, array_keys($dataCapaian));

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Juz',
                    'data' => $dataCapaian,
                    'backgroundColor' => $bgColors,
                    'borderRadius' => 8,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}