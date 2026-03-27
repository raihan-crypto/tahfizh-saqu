<?php

namespace App\Filament\Pages;

use App\Models\KelasHalaqah;
use App\Models\Santri;
use App\Models\Setoran;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class WaliSantriDashboard extends Page
{
    protected string $view = 'filament.pages.wali-santri-dashboard';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Wali Santri';
    protected static ?string $slug = 'wali-santri';
    protected static ?int $navigationSort = 0;

    public ?string $selectedSantriId = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'wali_santri';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'wali_santri';
    }

    protected function getKelasIds(): array
    {
        $user = auth()->user();
        return KelasHalaqah::where('nama_kelas', 'LIKE', $user->kelas_tingkat . '/%')
            ->pluck('id')
            ->toArray();
    }

    public function getKelasNamesProperty(): string
    {
        $user = auth()->user();
        $names = KelasHalaqah::where('nama_kelas', 'LIKE', $user->kelas_tingkat . '/%')
            ->pluck('nama_kelas')
            ->toArray();
        return implode(', ', $names);
    }

    public function getTotalSantriProperty(): int
    {
        return Santri::whereIn('kelas_halaqah_id', $this->getKelasIds())->count();
    }

    public function getTotalZiyadahProperty(): int
    {
        return Setoran::whereIn('santri_id', 
            Santri::whereIn('kelas_halaqah_id', $this->getKelasIds())->pluck('id')
        )->sum('ziyadah_baris');
    }

    public function getTotalMurajaahProperty(): int
    {
        return Setoran::whereIn('santri_id', 
            Santri::whereIn('kelas_halaqah_id', $this->getKelasIds())->pluck('id')
        )->sum('murajaah_baris');
    }

    public function getTopSantriProperty()
    {
        return Santri::whereIn('kelas_halaqah_id', $this->getKelasIds())
            ->with('kelasHalaqah')
            ->selectRaw('santris.*, (SELECT COALESCE(SUM(ziyadah_baris), 0) FROM setorans WHERE setorans.santri_id = santris.id) as total_baris')
            ->orderByDesc('total_baris')
            ->limit(10)
            ->get();
    }

    public function getBottomSantriProperty()
    {
        return Santri::whereIn('kelas_halaqah_id', $this->getKelasIds())
            ->with('kelasHalaqah')
            ->selectRaw('santris.*, (SELECT COALESCE(SUM(ziyadah_baris), 0) FROM setorans WHERE setorans.santri_id = santris.id) as total_baris')
            ->orderBy('total_baris')
            ->limit(10)
            ->get();
    }

    public function getSantriListProperty()
    {
        return Santri::whereIn('kelas_halaqah_id', $this->getKelasIds())
            ->with('kelasHalaqah')
            ->orderBy('nama_santri')
            ->get();
    }

    public function getZiyadahDataProperty(): array
    {
        if (!$this->selectedSantriId) return ['labels' => [], 'values' => []];

        $data = Setoran::where('santri_id', $this->selectedSantriId)
            ->where('tanggal', '>=', now()->subDays(30))
            ->select('tanggal', DB::raw('SUM(ziyadah_baris) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return [
            'labels' => $data->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray(),
            'values' => $data->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    public function getMurajaahDataProperty(): array
    {
        if (!$this->selectedSantriId) return ['labels' => [], 'values' => []];

        $data = Setoran::where('santri_id', $this->selectedSantriId)
            ->where('tanggal', '>=', now()->subDays(30))
            ->select('tanggal', DB::raw('SUM(murajaah_baris) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return [
            'labels' => $data->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray(),
            'values' => $data->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    public function getTrendDataProperty(): array
    {
        if (!$this->selectedSantriId) return ['labels' => [], 'daily' => [], 'cumulative' => []];

        $data = Setoran::where('santri_id', $this->selectedSantriId)
            ->where('tanggal', '>=', now()->subDays(30))
            ->select('tanggal', DB::raw('SUM(ziyadah_baris + rabth_baris + murajaah_baris) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = $data->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray();
        $daily = $data->pluck('total')->map(fn($v) => (int) $v)->toArray();
        
        $cumulative = [];
        $sum = 0;
        foreach ($daily as $v) {
            $sum += $v;
            $cumulative[] = $sum;
        }

        return [
            'labels' => $labels,
            'daily' => $daily,
            'cumulative' => $cumulative,
        ];
    }

    protected function getViewData(): array
    {
        return [
            'kelasNames' => $this->kelasNames,
            'totalSantri' => $this->totalSantri,
            'totalZiyadah' => $this->totalZiyadah,
            'totalMurajaah' => $this->totalMurajaah,
            'topSantri' => $this->topSantri,
            'bottomSantri' => $this->bottomSantri,
            'santriList' => $this->santriList,
            'ziyadahData' => $this->ziyadahData,
            'murajaahData' => $this->murajaahData,
            'trendData' => $this->trendData,
        ];
    }
}
