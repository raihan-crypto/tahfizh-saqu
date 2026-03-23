<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        // Menyamakan gray default Laravel Filament v3 (Zinc Color Scheme)
                        gray: {
                            50: '#fafafa',
                            100: '#f4f4f5',
                            200: '#e4e4e7',
                            300: '#d4d4d8',
                            400: '#a1a1aa',
                            500: '#71717a',
                            600: '#52525b',
                            700: '#3f3f46',
                            800: '#27272a',
                            900: '#18181b',
                            950: '#09090b',
                        }
                    }
                }
            }
        }
    </script>
    
    <div class="space-y-6">
        <!-- Controls -->
        <!-- Memakai dark:bg-white/5 dan dark:border-white/10 agar sama persis dengan Section/Card default Filament -->
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 bg-white dark:bg-white/5 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-white/10">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Laporan Rapor Tahfidz</h2>
            
            <div class="flex flex-wrap items-center gap-3">
                <select wire:model.live="santri_id" class="dark:bg-gray-800 border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-200 rounded-lg text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 min-w-[200px] outline-none">
                    <option value="">-- Pilih Santri --</option>
                    @foreach($this->santris as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_santri }}</option>
                    @endforeach
                </select>

                <select wire:model.live="bulan" class="dark:bg-gray-800 border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-200 rounded-lg text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 outline-none">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                <button onclick="window.print()" class="bg-gray-800 dark:bg-white/10 dark:text-gray-200 dark:hover:bg-white/20 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-700 text-sm font-semibold transition shadow print:hidden">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    PDF
                </button>
            </div>
        </div>

        @if($this->santri)
        @php
            $setorans = $this->setorans;
            
            $totalSemuaBaris = $this->santri->total_hafalan_baris;
            $juzAll = floor($totalSemuaBaris / 300);
            $sisaAll = $totalSemuaBaris % 300;
            $halAll = floor($sisaAll / 15);
            $persenTarget = min(100, ($totalSemuaBaris / (30 * 300)) * 100);

            $totalBarisBulanIni = $setorans->sum('ziyadah_baris');
            $juzBulanIni = floor($totalBarisBulanIni / 300);
            $sisaBulanIni = $totalBarisBulanIni % 300;
            $halBulanIni = floor($sisaBulanIni / 15);
            $teksBulanIni = ($juzBulanIni > 0 ? $juzBulanIni . ' Juz, ' : '') . $halBulanIni . ' Hal';

            $sHadir = $setorans->whereIn('kehadiran', ['Hadir', 'Terlambat'])->count();
            $sAbsen = $setorans->whereIn('kehadiran', ['Alpha', 'Izin', 'Sakit'])->count();
        @endphp

        <!-- 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
            <!-- Box 1 -->
            <div class="bg-white dark:bg-white/5 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-white/10 border-l-4 border-l-emerald-500 flex flex-col justify-center transition hover:shadow-md">
                <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Total Capaian Tahfidz</h3>
                <div class="text-3xl font-black text-emerald-600 dark:text-emerald-500 mb-3">{{ $juzAll }} Juz, {{ $halAll }} Hal</div>
                <div class="w-full bg-gray-100 dark:bg-white/10 rounded-full h-2.5 mb-2">
                    <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $persenTarget }}%"></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ number_format($persenTarget, 1) }}% dari target 30 Juz</p>
            </div>

            <!-- Box 2 -->
            <div class="bg-white dark:bg-white/5 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-white/10 border-l-4 border-l-blue-500 flex flex-col justify-center transition hover:shadow-md">
                <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-3">Hafalan Baru (Bulan Ini)</h3>
                <div class="text-3xl font-black text-blue-600 dark:text-blue-500 mb-3">{{ $teksBulanIni }}</div>
                <p class="text-xs text-blue-500 dark:text-blue-400 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Total {{ $totalBarisBulanIni }} baris sabaq disetorkan
                </p>
            </div>

            <!-- Box 3 -->
            <div class="bg-white dark:bg-white/5 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-white/10 border-l-4 border-l-amber-500 flex flex-col justify-center transition hover:shadow-md">
                <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-4">Kehadiran (Bulan Ini)</h3>
                <div class="flex gap-8">
                    <div class="text-center">
                        <div class="text-3xl font-black text-amber-600 dark:text-amber-500 leading-none mb-1">{{ $sHadir }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Hari Hadir</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-rose-500 dark:text-rose-400 leading-none mb-1">{{ $sAbsen }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Tidak Hadir</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matrix -->
        <div class="bg-white dark:bg-white/5 rounded-xl shadow-sm border border-gray-100 dark:border-white/10 overflow-hidden animate-fade-in print-break">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/10">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Rincian Setoran Harian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/5 text-gray-600 dark:text-gray-300 border-b border-gray-100 dark:border-white/10">
                            <th class="p-4 font-bold border-r border-gray-100 dark:border-white/5">Tanggal</th>
                            <th class="p-4 font-bold border-r border-gray-100 dark:border-white/5">Hadir</th>
                            <th class="p-4 font-bold border-r border-gray-100 dark:border-white/5">Sabaq (Baris)</th>
                            <th class="p-4 font-bold border-r border-gray-100 dark:border-white/5">Sabqi (Baris)</th>
                            <th class="p-4 font-bold border-r border-gray-100 dark:border-white/5">Manzil (Baris)</th>
                            <th class="p-4 font-bold border-r border-gray-100 dark:border-white/5">Nilai</th>
                            <th class="p-4 font-bold text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($setorans as $setoran)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <td class="p-4 border-r border-gray-100 dark:border-white/5 text-gray-600 dark:text-gray-400">{{ $setoran->tanggal }}</td>
                                <td class="p-4 border-r border-gray-100 dark:border-white/5">
                                    @if($setoran->kehadiran == 'Hadir')
                                        <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-bold flex inline-block w-max mx-auto">{{ $setoran->kehadiran }}</span>
                                    @elseif($setoran->kehadiran == 'Terlambat')
                                        <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-lg text-xs font-bold flex inline-block w-max mx-auto">{{ $setoran->kehadiran }}</span>
                                    @elseif($setoran->kehadiran == 'Sakit')
                                        <span class="px-3 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 rounded-lg text-xs font-bold flex inline-block w-max mx-auto">{{ $setoran->kehadiran }}</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-bold flex inline-block w-max mx-auto">{{ $setoran->kehadiran }}</span>
                                    @endif
                                </td>
                                @php $isHadir = in_array($setoran->kehadiran, ['Hadir', 'Terlambat']); @endphp
                                <td class="p-4 border-r border-gray-100 dark:border-white/5 text-gray-700 dark:text-gray-300 font-medium">{{ $isHadir ? $setoran->ziyadah_baris : '-' }}</td>
                                <td class="p-4 border-r border-gray-100 dark:border-white/5 text-gray-700 dark:text-gray-300 font-medium">{{ $isHadir ? $setoran->rabth_baris : '-' }}</td>
                                <td class="p-4 border-r border-gray-100 dark:border-white/5 text-gray-700 dark:text-gray-300 font-medium">{{ $isHadir ? $setoran->murajaah_baris : '-' }}</td>
                                <td class="p-4 font-extrabold text-gray-800 dark:text-gray-200 border-r border-gray-100 dark:border-white/5">{{ $isHadir ? $setoran->nilai_kelancaran : '-' }}</td>
                                <td class="p-4 text-left text-xs text-gray-500 dark:text-gray-400">{{ $setoran->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-400 dark:text-gray-500">Belum ada data setoran di bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
            <div class="bg-white dark:bg-white/5 p-10 rounded-xl shadow-sm border border-gray-100 dark:border-white/10 text-center text-gray-500 dark:text-gray-400">
                Silakan pilih santri terlebih dahulu untuk melihat laporan rapor.
            </div>
        @endif
        
        <style>
            .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @media print {
                .fi-topbar, .fi-sidebar, .fi-breadcrumbs { display: none !important; }
                .fi-main { padding: 0 !important; margin: 0 !important; }
                .grid { gap: 1rem !important; }
                .shadow-sm { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
                .print-break { margin-top: 2rem !important; }
                select { -webkit-appearance: none; appearance: none; border: none !important; font-weight: bold; background: transparent !important; padding: 0 !important; color: #000 !important; }
                select::-ms-expand { display: none; }
                body { background-color: white !important; }
                * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            }
        </style>
    </div>
</x-filament-panels::page>
