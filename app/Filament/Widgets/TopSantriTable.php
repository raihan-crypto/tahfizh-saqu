<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Split;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopSantriTable extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'half';
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'ustadz', 'guru']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Santri::query()
                    ->with('kelasHalaqah.ustadz')
                    ->selectRaw('santris.*, (SELECT COALESCE(SUM(ziyadah_baris), 0) FROM setorans WHERE setorans.santri_id = santris.id) as total_baris')
                    ->orderByDesc('total_baris')
                    ->limit(10)
            )
            ->heading(new \Illuminate\Support\HtmlString('<div style="display: flex; align-items: center; gap: 0.5rem; color: #10b981;"><svg style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg><span style="font-size: 1.125rem; font-weight: bold;">Top 10 Santri Terbaik</span></div>'))
            ->columns([
                Split::make([
                    Tables\Columns\TextColumn::make('index')
                        ->state(function ($record, \Filament\Widgets\TableWidget $livewire) {
                            return $livewire->getTableRecords()->search(fn ($item) => $item->id === $record->id) + 1;
                        })
                        ->label('')
                        ->grow(false)
                        ->formatStateUsing(function ($state) {
                            if ($state <= 3) {
                                return new \Illuminate\Support\HtmlString('<div style="width: 28px; height: 28px; border-radius: 50%; background-color: #fef08a; color: #b45309; display: flex; align-items: center; justify-content: center; font-weight: bold;">' . $state . '</div>');
                            }
                            return new \Illuminate\Support\HtmlString('<div style="width: 28px; height: 28px; border-radius: 50%; background-color: #f3f4f6; color: #4b5563; display: flex; align-items: center; justify-content: center; font-weight: bold;">' . $state . '</div>');
                        }),
                    Tables\Columns\TextColumn::make('nama_santri')
                        ->label('')
                            ->formatStateUsing(function (string $state, $record) {
                                $ustadzName = ($record->kelasHalaqah && $record->kelasHalaqah->ustadz) ? $record->kelasHalaqah->ustadz->nama_ustadz : '-';
                                $kelasName = $record->kelasHalaqah ? $record->kelasHalaqah->nama_kelas : '-';
                                return new \Illuminate\Support\HtmlString('<div style="display: flex; flex-direction: column;">
                                            <span style="font-weight: 500;">' . e($record->nama_santri) . '</span>
                                            <span style="font-size: 0.875rem; color: #9ca3af;">Kelas ' . e($kelasName) . ' &bull; Ust. ' . e($ustadzName) . '</span>
                                        </div>');
                            }),
                    Tables\Columns\TextColumn::make('total_baris')
                        ->label('')
                        ->grow(false)
                        ->formatStateUsing(function ($state) {
                            $totalBaris = (int) $state;
                            $juz = floor($totalBaris / 300); // 300 baris per juz
                            $sisa = $totalBaris % 300;
                            $halaman = floor($sisa / 15); // 15 baris per halaman
                            $baris = $sisa % 15;

                            $text = [];
                            if ($juz > 0) $text[] = "{$juz} Juz";
                            if ($halaman > 0) $text[] = "{$halaman} Halaman";
                            if ($baris > 0 || empty($text)) $text[] = "{$baris} Baris";

                            $string = implode(', ', $text);
                            return new \Illuminate\Support\HtmlString('<span style="background-color: #d1fae5; color: #059669; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: bold; font-size: 0.875rem; white-space: nowrap;">' . $string . '</span>');
                        }),
                ])->from('md'),
            ])->paginated(false);
    }
}