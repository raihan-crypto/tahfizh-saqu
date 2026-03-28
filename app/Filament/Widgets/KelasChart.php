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
        $labels = [];
        $dataCapaian = [];

        foreach ($kelasHalaqahs as $k) {
            $santriDiKelas = Santri::where('kelas_halaqah_id', $k->id)->count();
            
            if ($santriDiKelas > 0) {
                $totalBaris = DB::table('setorans')
                    ->join('santris', 'santris.id', '=', 'setorans.santri_id')
                    ->where('santris.kelas_halaqah_id', $k->id)
                    ->sum('setorans.ziyadah_baris');
                
                $rataRataBaris = $totalBaris / $santriDiKelas;
                $rataRataJuz = round($rataRataBaris / 300, 1);
            } else {
                $rataRataJuz = 0;
            }
            
            $labels[] = $k->nama_kelas;
            $dataCapaian[] = $rataRataJuz;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Juz',
                    'data' => $dataCapaian,
                    'backgroundColor' => '#f59e0b',
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