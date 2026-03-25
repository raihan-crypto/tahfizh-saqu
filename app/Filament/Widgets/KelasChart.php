<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Widgets\ChartWidget;

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
        $kelas = Santri::distinct()->pluck('kelas')->filter()->values()->toArray();
        $dataCapaian = [];

        foreach ($kelas as $k) {
            $santriDiKelas = Santri::where('kelas', $k)->count();
            
            if ($santriDiKelas > 0) {
                $totalBaris = \DB::table('setorans')
                    ->join('santris', 'santris.id', '=', 'setorans.santri_id')
                    ->where('santris.kelas', $k)
                    ->sum('setorans.ziyadah_baris');
                
                $rataRataBaris = $totalBaris / $santriDiKelas;
                $rataRataJuz = round($rataRataBaris / 300, 1);
            } else {
                $rataRataJuz = 0;
            }
            
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
            'labels' => $kelas,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}