<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .wali-card {
            background: white; border-radius: 1rem; border: 1px solid #e5e7eb;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06); transition: all 0.35s cubic-bezier(0.4,0,0.2,1); overflow: hidden;
        }
        .dark .wali-card { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); }
        .wali-card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.1); }
        .dark .wali-card:hover { box-shadow: 0 12px 40px rgba(0,0,0,0.3); }

        .wali-stat-card { padding: 1.25rem; position: relative; overflow: hidden; }
        .wali-stat-card .stat-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; }
        .wali-stat-card .stat-value { font-size: 1.75rem; font-weight: 900; letter-spacing: -0.02em; }
        .wali-stat-card .stat-label { font-size: 0.8rem; color: #6b7280; font-weight: 600; }
        .dark .wali-stat-card .stat-label { color: #9ca3af; }

        .rank-badge { width: 1.75rem; height: 1.75rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0; }
        .rank-gold { background: linear-gradient(135deg, #fef08a, #fbbf24); color: #92400e; box-shadow: 0 2px 8px rgba(251,191,36,0.3); }
        .rank-silver { background: linear-gradient(135deg, #e5e7eb, #d1d5db); color: #4b5563; }

        .rank-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.75rem; transition: all 0.2s ease; }
        .rank-row:hover { background: rgba(245,158,11,0.06); transform: translateX(4px); }

        .rank-pill { font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 9999px; white-space: nowrap; }
        .pill-green { background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(6,182,212,0.1)); color: #059669; }
        .pill-red { background: linear-gradient(135deg, rgba(239,68,68,0.15), rgba(244,63,94,0.1)); color: #e11d48; }
        .dark .pill-green { color: #6ee7b7; } .dark .pill-red { color: #fda4af; }

        .chart-card { background: #fafafa; border-radius: 0.75rem; padding: 1rem; border: 1px solid #f3f4f6; }
        .dark .chart-card { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08); }

        .wali-select {
            width: 100%; max-width: 24rem; padding: 0.5rem 0.75rem; border-radius: 0.75rem;
            border: 1px solid #d1d5db; background: white; color: #111827; font-size: 0.875rem;
            transition: all 0.2s ease; outline: none;
        }
        .wali-select:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.15); }
        .dark .wali-select { border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.05); color: inherit; }

        .animate-in { animation: fadeSlideUp 0.5s ease-out forwards; opacity: 0; }
        @keyframes fadeSlideUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .d1{animation-delay:.05s} .d2{animation-delay:.1s} .d3{animation-delay:.15s} .d4{animation-delay:.2s} .d5{animation-delay:.25s}

        .section-title { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
        .section-title svg { width: 1.25rem; height: 1.25rem; }
        .section-title span { font-size: 1rem; font-weight: 800; }
    </style>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Header Info --}}
        <div class="wali-card animate-in" style="padding: 0;">
            <div style="height: 4px; background: linear-gradient(90deg, #f59e0b, #ef4444, #8b5cf6);"></div>
            <div style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #f97316); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 900; box-shadow: 0 4px 14px rgba(245,158,11,0.3);">
                    {{ auth()->user()->kelas_tingkat }}
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; margin: 0;">{{ auth()->user()->name }}</h2>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">
                        Kelas {{ auth()->user()->kelas_tingkat }} — {{ $kelasNames }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div class="wali-card wali-stat-card animate-in d1">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="stat-label" style="margin: 0;">Jumlah Santri</p>
                        <p class="stat-value" style="margin: 0; color: #d97706;">{{ $totalSantri }}</p>
                    </div>
                </div>
            </div>
            <div class="wali-card wali-stat-card animate-in d2">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <p class="stat-label" style="margin: 0;">Total Ziyadah</p>
                        <p class="stat-value" style="margin: 0; color: #059669;">{{ number_format($totalZiyadah) }}</p>
                    </div>
                </div>
            </div>
            <div class="wali-card wali-stat-card animate-in d3">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div>
                        <p class="stat-label" style="margin: 0;">Total Murajaah</p>
                        <p class="stat-value" style="margin: 0; color: #2563eb;">{{ number_format($totalMurajaah) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top & Bottom Rankings --}}
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
            <div class="wali-card animate-in d4" style="padding: 1.25rem;">
                <div class="section-title" style="color: #059669;">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span>Top 10 Santri Terbaik</span>
                </div>
                @foreach($topSantri as $idx => $s)
                    <div class="rank-row">
                        <div class="rank-badge {{ $idx < 3 ? 'rank-gold' : 'rank-silver' }}">{{ $idx + 1 }}</div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 600; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $s->nama_santri }}</p>
                            <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">{{ $s->kelasHalaqah?->nama_kelas ?? '-' }}</p>
                        </div>
                        <span class="rank-pill pill-green">{{ number_format($s->total_baris) }} Baris</span>
                    </div>
                @endforeach
            </div>

            <div class="wali-card animate-in d5" style="padding: 1.25rem;">
                <div class="section-title" style="color: #e11d48;">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Top 10 Butuh Perhatian</span>
                </div>
                @foreach($bottomSantri as $idx => $s)
                    <div class="rank-row">
                        <div class="rank-badge {{ $idx < 3 ? 'rank-gold' : 'rank-silver' }}" style="{{ $idx < 3 ? 'background: linear-gradient(135deg, #fecaca, #fca5a5); color: #991b1b; box-shadow: 0 2px 8px rgba(239,68,68,0.2);' : '' }}">{{ $idx + 1 }}</div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 600; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $s->nama_santri }}</p>
                            <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">{{ $s->kelasHalaqah?->nama_kelas ?? '-' }}</p>
                        </div>
                        <span class="rank-pill pill-red">{{ number_format($s->total_baris) }} Baris</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Data Spesifik Anak --}}
        <div class="wali-card animate-in" style="padding: 1.25rem;">
            <div class="section-title" style="color: #d97706;">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                <span>Data Spesifik Anak</span>
            </div>
            <select class="wali-select" wire:model.live="selectedSantriId" style="margin-bottom: 1rem;">
                <option value="">— Pilih Nama Santri —</option>
                @foreach($santriList as $s)
                    <option value="{{ $s->id }}">{{ $s->nama_santri }} ({{ $s->kelasHalaqah?->nama_kelas ?? '-' }})</option>
                @endforeach
            </select>

            @if($selectedSantriId && $selectedSantriData)
                <div style="padding: 0.75rem 1rem; border-radius: 0.75rem; background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(245,158,11,0.03)); border: 1px solid rgba(245,158,11,0.2); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, #f59e0b, #f97316); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800;">
                        {{ substr($selectedSantriData['nama'], 0, 1) }}
                    </div>
                    <div>
                        <p style="font-weight: 700; margin: 0;">{{ $selectedSantriData['nama'] }}</p>
                        <p style="font-size: 0.8rem; color: #6b7280; margin: 0;">Kelas {{ $selectedSantriData['kelas'] }} &bull; Ziyadah: {{ number_format($selectedSantriData['total_ziyadah']) }} baris &bull; Murajaah: {{ number_format($selectedSantriData['total_murajaah']) }} baris</p>
                    </div>
                </div>

                <div
                    x-data="{
                        ziyadah: @js($ziyadahData),
                        murajaah: @js($murajaahData),
                        trend: @js($trendData),
                        renderCharts() {
                            this.$nextTick(() => {
                                ['waliZiyadahChart', 'waliMurajaahChart', 'waliTrendChart'].forEach(id => {
                                    const c = document.getElementById(id);
                                    if (c) { const e = Chart.getChart(c); if (e) e.destroy(); }
                                });
                                const zc = document.getElementById('waliZiyadahChart');
                                if (zc) new Chart(zc, { type: 'line', data: { labels: this.ziyadah.labels, datasets: [{ label: 'Ziyadah (Baris)', data: this.ziyadah.values, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: '#10b981' }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } }, x: { grid: { display: false } } } } });
                                const mc = document.getElementById('waliMurajaahChart');
                                if (mc) new Chart(mc, { type: 'line', data: { labels: this.murajaah.labels, datasets: [{ label: 'Murajaah (Baris)', data: this.murajaah.values, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: '#3b82f6' }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } }, x: { grid: { display: false } } } } });
                                const tc = document.getElementById('waliTrendChart');
                                if (tc) new Chart(tc, { type: 'line', data: { labels: this.trend.labels, datasets: [{ label: 'Total Hafalan (Kumulatif)', data: this.trend.cumulative, borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.08)', fill: true, tension: 0.4, pointRadius: 2, pointBackgroundColor: '#8b5cf6' }, { label: 'Harian', data: this.trend.daily, borderColor: '#f59e0b', fill: false, tension: 0.4, pointRadius: 2, borderDash: [5,5], pointBackgroundColor: '#f59e0b' }] }, options: { responsive: true, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } }, x: { grid: { display: false } } } } });
                            });
                        }
                    }"
                    x-init="renderCharts()"
                    wire:key="charts-{{ $selectedSantriId }}"
                >
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-top: 1rem;">
                        <div class="chart-card">
                            <h4 style="font-size: 0.875rem; font-weight: 800; color: #059669; margin: 0 0 0.75rem 0;">📈 Ziyadah (Hafalan Baru) — 30 Hari</h4>
                            <canvas id="waliZiyadahChart" height="200"></canvas>
                        </div>
                        <div class="chart-card">
                            <h4 style="font-size: 0.875rem; font-weight: 800; color: #2563eb; margin: 0 0 0.75rem 0;">📈 Murajaah (Ulangan) — 30 Hari</h4>
                            <canvas id="waliMurajaahChart" height="200"></canvas>
                        </div>
                    </div>
                    <div class="chart-card" style="margin-top: 1rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 800; color: #7c3aed; margin: 0 0 0.75rem 0;">📊 Tren Pencapaian Hafalan (Kumulatif Harian)</h4>
                        <canvas id="waliTrendChart" height="150"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
