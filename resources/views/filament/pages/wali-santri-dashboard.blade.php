<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Header Info --}}
        <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-950/30 border border-primary-200 dark:border-primary-800">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ auth()->user()->kelas_tingkat }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Kelas {{ auth()->user()->kelas_tingkat }} — {{ $kelasNames }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Santri</p>
                <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $totalSantri }}</p>
            </div>
            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Baris Ziyadah</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalZiyadah) }}</p>
            </div>
            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Baris Murajaah</p>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalMurajaah) }}</p>
            </div>
        </div>

        {{-- Top 10 & Bottom 10 Side by Side --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top 10 Terbaik --}}
            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-bold text-green-600 dark:text-green-400 mb-3 flex items-center gap-2">
                    <x-heroicon-o-trophy class="w-5 h-5" /> Top 10 Santri Terbaik
                </h3>
                <div class="space-y-2">
                    @foreach($topSantri as $idx => $s)
                        <div class="flex items-center gap-3 p-2 rounded-lg {{ $idx < 3 ? 'bg-amber-50 dark:bg-amber-950/30' : '' }}">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $idx < 3 ? 'bg-amber-200 text-amber-800 dark:bg-amber-800 dark:text-amber-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                                {{ $idx + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $s->nama_santri }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $s->kelasHalaqah?->nama_kelas ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 whitespace-nowrap">
                                {{ number_format($s->total_baris) }} Baris
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bottom 10 Butuh Perhatian --}}
            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-bold text-red-600 dark:text-red-400 mb-3 flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" /> Top 10 Butuh Perhatian
                </h3>
                <div class="space-y-2">
                    @foreach($bottomSantri as $idx => $s)
                        <div class="flex items-center gap-3 p-2 rounded-lg {{ $idx < 3 ? 'bg-red-50 dark:bg-red-950/30' : '' }}">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $idx < 3 ? 'bg-red-200 text-red-800 dark:bg-red-800 dark:text-red-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                                {{ $idx + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $s->nama_santri }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $s->kelasHalaqah?->nama_kelas ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 whitespace-nowrap">
                                {{ number_format($s->total_baris) }} Baris
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pilih Anak Section --}}
        <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <x-heroicon-o-user class="w-5 h-5" /> Data Spesifik Anak
            </h3>
            <div class="mb-4">
                <select id="santri-select" wire:model.live="selectedSantriId" class="w-full md:w-1/3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">— Pilih Nama Santri —</option>
                    @foreach($santriList as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_santri }} ({{ $s->kelasHalaqah?->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            @if($selectedSantriId)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
                    {{-- Grafik Ziyadah --}}
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-green-600 dark:text-green-400 mb-3">📈 Ziyadah (Hafalan Baru) — 30 Hari</h4>
                        <canvas id="ziyadahChart" height="200"></canvas>
                    </div>

                    {{-- Grafik Murajaah --}}
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400 mb-3">📈 Murajaah (Ulangan) — 30 Hari</h4>
                        <canvas id="murajaahChart" height="200"></canvas>
                    </div>
                </div>

                {{-- Grafik Tren Bulanan --}}
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 mt-4">
                    <h4 class="text-sm font-bold text-purple-600 dark:text-purple-400 mb-3">📊 Tren Pencapaian Hafalan (Kumulatif Harian)</h4>
                    <canvas id="trendChart" height="150"></canvas>
                </div>
            @endif
        </div>
    </div>

    @if($selectedSantriId)
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:navigated', () => { renderCharts(); });
            document.addEventListener('livewire:init', () => {
                Livewire.hook('morph.updated', () => { setTimeout(renderCharts, 100); });
            });

            function renderCharts() {
                const ziyadahData = @json($ziyadahData);
                const murajaahData = @json($murajaahData);
                const trendData = @json($trendData);

                // Destroy existing charts if present
                ['ziyadahChart', 'murajaahChart', 'trendChart'].forEach(id => {
                    const canvas = document.getElementById(id);
                    if (canvas) {
                        const existing = Chart.getChart(canvas);
                        if (existing) existing.destroy();
                    }
                });

                // Ziyadah Chart
                const ziyadahCanvas = document.getElementById('ziyadahChart');
                if (ziyadahCanvas) {
                    new Chart(ziyadahCanvas, {
                        type: 'line',
                        data: {
                            labels: ziyadahData.labels,
                            datasets: [{
                                label: 'Ziyadah (Baris)',
                                data: ziyadahData.values,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                            }]
                        },
                        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                    });
                }

                // Murajaah Chart
                const murajaahCanvas = document.getElementById('murajaahChart');
                if (murajaahCanvas) {
                    new Chart(murajaahCanvas, {
                        type: 'line',
                        data: {
                            labels: murajaahData.labels,
                            datasets: [{
                                label: 'Murajaah (Baris)',
                                data: murajaahData.values,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                            }]
                        },
                        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                    });
                }

                // Trend Chart
                const trendCanvas = document.getElementById('trendChart');
                if (trendCanvas) {
                    new Chart(trendCanvas, {
                        type: 'line',
                        data: {
                            labels: trendData.labels,
                            datasets: [
                                {
                                    label: 'Total Hafalan (Kumulatif)',
                                    data: trendData.cumulative,
                                    borderColor: '#8b5cf6',
                                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 2,
                                },
                                {
                                    label: 'Harian',
                                    data: trendData.daily,
                                    borderColor: '#f59e0b',
                                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                    fill: false,
                                    tension: 0.4,
                                    pointRadius: 2,
                                    borderDash: [5, 5],
                                }
                            ]
                        },
                        options: { responsive: true, scales: { y: { beginAtZero: true } } }
                    });
                }
            }

            // Initial render
            renderCharts();
        </script>
        @endpush
    @endif
</x-filament-panels::page>
