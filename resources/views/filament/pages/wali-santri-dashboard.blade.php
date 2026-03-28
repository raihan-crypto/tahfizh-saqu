<x-filament-panels::page>
    {{-- Load Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .wali-card {
            padding: 1rem;
            border-radius: 0.75rem;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        }
        .dark .wali-card {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: none;
        }

        .wali-card-alt {
            padding: 1rem;
            border-radius: 0.75rem;
            background-color: #f9fafb;
            border: 1px solid #f3f4f6;
        }
        .dark .wali-card-alt {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .wali-text-muted { color: #6b7280; }
        .dark .wali-text-muted { color: #9ca3af; }

        .rank-top-3 { background-color: #d1fae5; color: #065f46; }
        .rank-other { background-color: #f3f4f6; color: #4b5563; }
        .dark .rank-other { background-color: rgba(255, 255, 255, 0.1); color: #9ca3af; }

        .rank-row-top-3 { background-color: #ecfdf5; }
        .dark .rank-row-top-3 { background-color: rgba(16, 185, 129, 0.1); }

        .bottom-rank-top-3 { background-color: #fee2e2; color: #991b1b; }
        .bottom-row-top-3 { background-color: #fef2f2; }
        .dark .bottom-row-top-3 { background-color: rgba(239, 68, 68, 0.1); }

        .wali-input {
            width: 100%;
            max-width: 24rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #111827;
            font-size: 0.875rem;
        }
        .dark .wali-input {
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.05);
            color: inherit;
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Header Info --}}
        <div style="padding: 1rem; border-radius: 0.75rem; background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05)); border: 1px solid rgba(245,158,11,0.3);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 3rem; height: 3rem; border-radius: 50%; background: #f59e0b; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; font-weight: bold; box-shadow: 0 4px 6px -1px rgba(245,158,11,0.3);">
                    {{ auth()->user()->kelas_tingkat }}
                </div>
                <div>
                    <h2 style="font-size: 1.125rem; font-weight: bold; margin: 0;">{{ auth()->user()->name }}</h2>
                    <p class="wali-text-muted" style="font-size: 0.875rem; margin: 0;">
                        Kelas {{ auth()->user()->kelas_tingkat }} — {{ $kelasNames }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div class="wali-card">
                <p class="wali-text-muted" style="font-size: 0.875rem; margin: 0 0 0.25rem 0;">Jumlah Santri</p>
                <p style="font-size: 1.5rem; font-weight: bold; color: #f59e0b; margin: 0;">{{ $totalSantri }}</p>
            </div>
            <div class="wali-card">
                <p class="wali-text-muted" style="font-size: 0.875rem; margin: 0 0 0.25rem 0;">Total Baris Ziyadah</p>
                <p style="font-size: 1.5rem; font-weight: bold; color: #10b981; margin: 0;">{{ number_format($totalZiyadah) }}</p>
            </div>
            <div class="wali-card">
                <p class="wali-text-muted" style="font-size: 0.875rem; margin: 0 0 0.25rem 0;">Total Baris Murajaah</p>
                <p style="font-size: 1.5rem; font-weight: bold; color: #3b82f6; margin: 0;">{{ number_format($totalMurajaah) }}</p>
            </div>
        </div>

        {{-- Top 10 & Bottom 10 --}}
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="wali-card">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #10b981; margin-bottom: 0.75rem;">
                    <svg style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                    <span style="font-size: 1.125rem; font-weight: bold;">Top 10 Santri Terbaik</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($topSantri as $idx => $s)
                        <div class="{{ $idx < 3 ? 'rank-row-top-3' : '' }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 0.5rem;">
                            <div class="{{ $idx < 3 ? 'rank-top-3' : 'rank-other' }}" style="width: 1.75rem; height: 1.75rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">
                                {{ $idx + 1 }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <p style="font-size: 0.875rem; font-weight: 500; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $s->nama_santri }}</p>
                                <p class="wali-text-muted" style="font-size: 0.75rem; margin: 0;">Kelas {{ $s->kelasHalaqah?->nama_kelas ?? '-' }}</p>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: bold; padding: 0.25rem 0.75rem; border-radius: 9999px; background: rgba(16,185,129,0.15); color: #10b981; white-space: nowrap;">
                                {{ number_format($s->total_baris) }} Baris
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="wali-card">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #ef4444; margin-bottom: 0.75rem;">
                    <svg style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span style="font-size: 1.125rem; font-weight: bold;">Top 10 Butuh Perhatian</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($bottomSantri as $idx => $s)
                        <div class="{{ $idx < 3 ? 'bottom-row-top-3' : '' }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 0.5rem;">
                            <div class="{{ $idx < 3 ? 'bottom-rank-top-3' : 'rank-other' }}" style="width: 1.75rem; height: 1.75rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">
                                {{ $idx + 1 }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <p style="font-size: 0.875rem; font-weight: 500; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $s->nama_santri }}</p>
                                <p class="wali-text-muted" style="font-size: 0.75rem; margin: 0;">Kelas {{ $s->kelasHalaqah?->nama_kelas ?? '-' }}</p>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: bold; padding: 0.25rem 0.75rem; border-radius: 9999px; background: rgba(239,68,68,0.15); color: #ef4444; white-space: nowrap;">
                                {{ number_format($s->total_baris) }} Baris
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Data Spesifik Anak --}}
        <div class="wali-card">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <svg style="width: 1.25rem; height: 1.25rem; color: #f59e0b;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                <span style="font-size: 1rem; font-weight: bold;">Data Spesifik Anak</span>
            </div>
            <div style="margin-bottom: 1rem;">
                <select class="wali-input" wire:model.live="selectedSantriId">
                    <option value="">— Pilih Nama Santri —</option>
                    @foreach($santriList as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_santri }} ({{ $s->kelasHalaqah?->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            @if($selectedSantriId && $selectedSantriData)
                <div style="padding: 0.75rem; border-radius: 0.5rem; background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #f59e0b; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        {{ substr($selectedSantriData['nama'], 0, 1) }}
                    </div>
                    <div>
                        <p style="font-weight: 600; margin: 0;">{{ $selectedSantriData['nama'] }}</p>
                        <p class="wali-text-muted" style="font-size: 0.8rem; margin: 0;">Kelas {{ $selectedSantriData['kelas'] }} &bull; Total Ziyadah: {{ number_format($selectedSantriData['total_ziyadah']) }} baris &bull; Total Murajaah: {{ number_format($selectedSantriData['total_murajaah']) }} baris</p>
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
                                if (zc) new Chart(zc, { type: 'line', data: { labels: this.ziyadah.labels, datasets: [{ label: 'Ziyadah (Baris)', data: this.ziyadah.values, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4, pointRadius: 3 }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });

                                const mc = document.getElementById('waliMurajaahChart');
                                if (mc) new Chart(mc, { type: 'line', data: { labels: this.murajaah.labels, datasets: [{ label: 'Murajaah (Baris)', data: this.murajaah.values, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', fill: true, tension: 0.4, pointRadius: 3 }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });

                                const tc = document.getElementById('waliTrendChart');
                                if (tc) new Chart(tc, { type: 'line', data: { labels: this.trend.labels, datasets: [{ label: 'Total Hafalan (Kumulatif)', data: this.trend.cumulative, borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.1)', fill: true, tension: 0.4, pointRadius: 2 }, { label: 'Harian', data: this.trend.daily, borderColor: '#f59e0b', fill: false, tension: 0.4, pointRadius: 2, borderDash: [5,5] }] }, options: { responsive: true, scales: { y: { beginAtZero: true } } } });
                            });
                        }
                    }"
                    x-init="renderCharts()"
                    wire:key="charts-{{ $selectedSantriId }}"
                >
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-top: 1rem;">
                        <div class="wali-card-alt">
                            <h4 style="font-size: 0.875rem; font-weight: bold; color: #10b981; margin: 0 0 0.75rem 0;">📈 Ziyadah (Hafalan Baru) — 30 Hari</h4>
                            <canvas id="waliZiyadahChart" height="200"></canvas>
                        </div>
                        <div class="wali-card-alt">
                            <h4 style="font-size: 0.875rem; font-weight: bold; color: #3b82f6; margin: 0 0 0.75rem 0;">📈 Murajaah (Ulangan) — 30 Hari</h4>
                            <canvas id="waliMurajaahChart" height="200"></canvas>
                        </div>
                    </div>
                    <div class="wali-card-alt" style="margin-top: 1rem;">
                        <h4 style="font-size: 0.875rem; font-weight: bold; color: #8b5cf6; margin: 0 0 0.75rem 0;">📊 Tren Pencapaian Hafalan (Kumulatif Harian)</h4>
                        <canvas id="waliTrendChart" height="150"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
