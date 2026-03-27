<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Santri;
use App\Models\Setoran;

class LaporanBulanan extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Bulanan';
    protected static ?string $slug = 'laporan-bulanan';
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.pages.laporan-bulanan';
    protected static ?string $title = 'Laporan Rapor Tahfidz';

    public $santri_id;
    public $bulan;

    public function mount()
    {
        $user = auth()->user();
        if ($user->role === 'wali_santri') {
            $kelasIds = $user->kelasHalaqahIds();
            $firstSantri = Santri::whereIn('kelas_halaqah_id', $kelasIds)->first();
        } elseif ($user->role === 'guru' && $user->ustadz_id) {
            $kelasIds = $user->guruKelasHalaqahIds();
            $firstSantri = Santri::whereIn('kelas_halaqah_id', $kelasIds)->first();
        } else {
            $firstSantri = Santri::first();
        }
        
        $this->santri_id = $firstSantri ? $firstSantri->id : null;
        $this->bulan = date('m');
    }

    public function getSantrisProperty()
    {
        $user = auth()->user();
        if ($user->role === 'wali_santri') {
            $kelasIds = $user->kelasHalaqahIds();
            return Santri::whereIn('kelas_halaqah_id', $kelasIds)->orderBy('nama_santri')->get();
        }
        if ($user->role === 'guru' && $user->ustadz_id) {
            $kelasIds = $user->guruKelasHalaqahIds();
            return Santri::whereIn('kelas_halaqah_id', $kelasIds)->orderBy('nama_santri')->get();
        }
        return Santri::orderBy('nama_santri')->get();
    }

    public function getSantriProperty()
    {
        return Santri::find($this->santri_id);
    }

    public function getSetoransProperty()
    {
        if (!$this->santri_id) return collect();

        return Setoran::where('santri_id', $this->santri_id)
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', date('Y'))
            ->orderBy('tanggal')
            ->get();
    }
}
