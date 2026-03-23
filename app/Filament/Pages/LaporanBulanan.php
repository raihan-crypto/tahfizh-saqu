<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Santri;
use App\Models\Setoran;

class LaporanBulanan extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected string $view = 'filament.pages.laporan-bulanan';
    protected static ?string $title = 'Laporan Rapor Tahfidz';

    public $santri_id;
    public $bulan;

    public function mount()
    {
        // For Wali Murid privacy
        if (auth()->user()->role === 'wali_murid') {
            $firstSantri = Santri::where('user_id', auth()->id())->first();
        } else {
            $firstSantri = Santri::first();
        }
        
        $this->santri_id = $firstSantri ? $firstSantri->id : null;
        $this->bulan = date('m');
    }

    public function getSantrisProperty()
    {
        if (auth()->user()->role === 'wali_murid') {
            return Santri::where('user_id', auth()->id())->orderBy('nama_santri')->get();
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
