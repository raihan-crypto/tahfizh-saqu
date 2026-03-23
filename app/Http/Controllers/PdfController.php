<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function laporan(Request $request)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        $kelas = $request->get('kelas');
        $ustadz = $request->get('ustadz');

        $santris = Santri::with(['ustadz', 'setorans' => function($q) use ($bulan) {
            $q->whereMonth('tanggal', date('m', strtotime($bulan)))
              ->whereYear('tanggal', date('Y', strtotime($bulan)));
        }])->when($kelas, function($q) use ($kelas) {
            $q->where('kelas_halaqah', $kelas);
        })->when($ustadz, function($q) use ($ustadz) {
            $q->where('ustadz_id', $ustadz);
        })->get();

        $pdf = Pdf::loadView('laporan-pdf', compact('santris', 'bulan'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-setoran-' . $bulan . '.pdf');
    }
}
