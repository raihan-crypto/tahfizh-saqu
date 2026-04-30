<x-filament-panels::page>
    <style>
        .saqu-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .dark .saqu-card {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.1);
        }
        .saqu-card:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,0.1); }
        .dark .saqu-card:hover { box-shadow: 0 12px 40px rgba(0,0,0,0.3); }

        .saqu-gradient-bar { height: 4px; border-radius: 4px 4px 0 0; }
        .bar-emerald { background: linear-gradient(90deg, #10b981, #06b6d4); }
        .bar-blue { background: linear-gradient(90deg, #3b82f6, #8b5cf6); }
        .bar-amber { background: linear-gradient(90deg, #f59e0b, #ef4444); }

        .saqu-stat-value {
            font-size: 2rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .saqu-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .badge-hadir { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
        .badge-terlambat { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
        .badge-sakit { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #9d174d; }
        .badge-default { background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #4b5563; }
        .dark .badge-hadir { background: rgba(16,185,129,0.2); color: #6ee7b7; }
        .dark .badge-terlambat { background: rgba(245,158,11,0.2); color: #fcd34d; }
        .dark .badge-sakit { background: rgba(244,63,94,0.2); color: #fda4af; }
        .dark .badge-default { background: rgba(255,255,255,0.1); color: #d1d5db; }

        .saqu-table th { padding: 1rem; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .saqu-table td { padding: 0.875rem 1rem; }
        .saqu-table tbody tr { transition: background 0.2s ease; }
        .saqu-table tbody tr:hover { background: rgba(245,158,11,0.04); }
        .dark .saqu-table tbody tr:hover { background: rgba(245,158,11,0.06); }

        .saqu-select {
            background: white; border: 1px solid #d1d5db; border-radius: 0.75rem;
            padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151;
            transition: all 0.2s ease; outline: none; min-width: 180px;
        }
        .saqu-select:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.15); }
        .dark .saqu-select { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.15); color: #e5e7eb; }

        .saqu-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600;
            background: linear-gradient(135deg, #f59e0b, #f97316); color: white; border: none; cursor: pointer;
            transition: all 0.25s ease; box-shadow: 0 4px 14px rgba(245,158,11,0.3);
        }
        .saqu-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.4); }

        .progress-ring { width: 100%; height: 0.625rem; background: #f3f4f6; border-radius: 9999px; overflow: hidden; }
        .dark .progress-ring { background: rgba(255,255,255,0.1); }
        .progress-ring-fill { height: 100%; border-radius: 9999px; background: linear-gradient(90deg, #10b981, #06b6d4); transition: width 1s ease-out; }

        .animate-in { animation: fadeSlideUp 0.5s ease-out forwards; opacity: 0; }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        @media print {
            .fi-topbar, .fi-sidebar, .fi-breadcrumbs, .print-hide { display: none !important; }
            .fi-main { padding: 0 !important; margin: 0 !important; }
            .saqu-card { box-shadow: none !important; border: 1px solid #d1d5db !important; break-inside: avoid; }
            body { background: white !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        {{-- Header Controls --}}
        <div class="saqu-card animate-in" style="padding: 1.25rem;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; margin: 0; background: linear-gradient(135deg, #f59e0b, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Laporan Rapor Tahfidz</h2>
                </div>
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem;" class="print-hide">
                    <select wire:model.live="santri_id" class="saqu-select">
                        <option value="">— Pilih Santri —</option>
                        @foreach($this->santris as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_santri }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="bulan" class="saqu-select" style="min-width: 140px;">
                        <option value="01">Januari</option><option value="02">Februari</option>
                        <option value="03">Maret</option><option value="04">April</option>
                        <option value="05">Mei</option><option value="06">Juni</option>
                        <option value="07">Juli</option><option value="08">Agustus</option>
                        <option value="09">September</option><option value="10">Oktober</option>
                        <option value="11">November</option><option value="12">Desember</option>
                    </select>
                    <button onclick="window.print()" class="saqu-btn">
                        <svg style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak PDF
                    </button>
                </div>
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

        {{-- Stats Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
            {{-- Card 1: Total Capaian --}}
            <div class="saqu-card animate-in delay-1">
                <div class="saqu-gradient-bar bar-emerald"></div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #d1fae5, #a7f3d0); display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; color: #059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Total Capaian</span>
                    </div>
                    <div class="saqu-stat-value" style="color: #059669;">{{ $juzAll }} Juz, {{ $halAll }} Hal</div>
                    <div class="progress-ring" style="margin-top: 1rem;">
                        <div class="progress-ring-fill" style="width: {{ $persenTarget }}%"></div>
                    </div>
                    <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.5rem; font-weight: 500;">{{ number_format($persenTarget, 1) }}% dari target 30 Juz</p>
                </div>
            </div>

            {{-- Card 2: Hafalan Baru --}}
            <div class="saqu-card animate-in delay-2">
                <div class="saqu-gradient-bar bar-blue"></div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #dbeafe, #bfdbfe); display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; color: #2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Hafalan Baru</span>
                    </div>
                    <div class="saqu-stat-value" style="color: #2563eb;">{{ $teksBulanIni }}</div>
                    <p style="font-size: 0.75rem; color: #60a5fa; margin-top: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 0.25rem;">
                        <svg style="width: 0.875rem; height: 0.875rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        {{ $totalBarisBulanIni }} baris sabaq bulan ini
                    </p>
                </div>
            </div>

            {{-- Card 3: Kehadiran --}}
            <div class="saqu-card animate-in delay-3">
                <div class="saqu-gradient-bar bar-amber"></div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #fef3c7, #fde68a); display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 1rem; height: 1rem; color: #d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Kehadiran</span>
                    </div>
                    <div style="display: flex; gap: 2rem; margin-top: 0.5rem;">
                        <div style="text-align: center;">
                            <div class="saqu-stat-value" style="color: #059669;">{{ $sHadir }}</div>
                            <span style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">Hadir</span>
                        </div>
                        <div style="text-align: center;">
                            <div class="saqu-stat-value" style="color: #e11d48;">{{ $sAbsen }}</div>
                            <span style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">Tidak Hadir</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Table --}}
        <div class="saqu-card animate-in delay-4">
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 0.25rem; height: 1.5rem; border-radius: 9999px; background: linear-gradient(180deg, #f59e0b, #ef4444);"></div>
                <h3 style="font-size: 1rem; font-weight: 800; margin: 0; color: #1f2937;">Rincian Setoran Harian</h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="saqu-table" style="width: 100%; text-align: center; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #fafafa; border-bottom: 2px solid #f3f4f6;">
                            <th style="color: #6b7280;">Tanggal</th>
                            <th style="color: #6b7280;">Status</th>
                            <th style="color: #6b7280;">Sabaq</th>
                            <th style="color: #6b7280;">Sabqi</th>
                            <th style="color: #6b7280;">Manzil</th>
                            <th style="color: #6b7280;">Nilai</th>
                            <th style="color: #6b7280; text-align: left;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setorans as $setoran)
                            <tr style="border-bottom: 1px solid #f9fafb;">
                                <td style="color: #6b7280; font-weight: 500;">{{ $setoran->tanggal }}</td>
                                <td>
                                    @if($setoran->kehadiran == 'Hadir')
                                        <span class="saqu-badge badge-hadir">{{ $setoran->kehadiran }}</span>
                                    @elseif($setoran->kehadiran == 'Terlambat')
                                        <span class="saqu-badge badge-terlambat">{{ $setoran->kehadiran }}</span>
                                    @elseif($setoran->kehadiran == 'Sakit')
                                        <span class="saqu-badge badge-sakit">{{ $setoran->kehadiran }}</span>
                                    @else
                                        <span class="saqu-badge badge-default">{{ $setoran->kehadiran }}</span>
                                    @endif
                                </td>
                                @php $isHadir = in_array($setoran->kehadiran, ['Hadir', 'Terlambat']); @endphp
                                <td style="font-weight: 600; color: #374151;">{{ $isHadir ? $setoran->ziyadah_baris : '-' }}</td>
                                <td style="font-weight: 600; color: #374151;">{{ $isHadir ? $setoran->rabth_baris : '-' }}</td>
                                <td style="font-weight: 600; color: #374151;">{{ $isHadir ? $setoran->murajaah_baris : '-' }}</td>
                                <td style="font-weight: 800; color: #111827;">{{ $isHadir ? $setoran->nilai_kelancaran : '-' }}</td>
                                <td style="text-align: left; font-size: 0.8rem; color: #9ca3af;">{{ $setoran->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 3rem; color: #9ca3af; font-style: italic;">
                                    <svg style="width: 2rem; height: 2rem; margin: 0 auto 0.5rem; color: #d1d5db;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    Belum ada data setoran di bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
            <div class="saqu-card animate-in" style="padding: 3rem; text-align: center;">
                <div style="width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: linear-gradient(135deg, #fef3c7, #fde68a); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 2rem; height: 2rem; color: #d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <p style="color: #6b7280; font-weight: 500;">Silakan pilih santri terlebih dahulu untuk melihat laporan rapor.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
