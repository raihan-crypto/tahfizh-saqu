<!DOCTYPE html>
<html>
<head>
    <title>Laporan Setoran Bulanan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="text-center">Laporan Bulanan Setoran Tahfizh SaQu UMA</h2>
    <p class="text-center">Bulan: {{ date('F Y', strtotime($bulan)) }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Santri</th>
                <th>Kelas / Halaqah</th>
                <th>Ustadz</th>
                <th>Ziyadah Bulan Ini</th>
                <th>Kehadiran Hadir / Tidak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($santris as $i => $santri)
                @php
                    $totalZiyadahBaris = $santri->setorans->sum('ziyadah_baris');
                    $totalHadir = $santri->setorans->where('kehadiran', 'Hadir')->count();
                    $totalTidak = $santri->setorans->where('kehadiran', '!=', 'Hadir')->count();
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $santri->nama_santri }}</td>
                    <td>{{ $santri->kelas }} / {{ $santri->kelas_halaqah }}</td>
                    <td>{{ optional($santri->ustadz)->nama_ustadz ?? '-' }}</td>
                    <td>{{ floor($totalZiyadahBaris/15) }} Hal ({{ $totalZiyadahBaris }} Brs)</td>
                    <td>{{ $totalHadir }} / {{ $totalTidak }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
