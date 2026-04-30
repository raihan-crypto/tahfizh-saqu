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
            ->heading(new \Illuminate\Support\HtmlString('<div style="display: flex; align-items: center; gap: 0.5rem;"><div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #d1fae5, #a7f3d0); display: flex; align-items: center; justify-content: center;"><svg style="width: 1rem; height: 1rem; color: #059669;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div><span style="font-size: 1rem; font-weight: 800; color: #059669;">Top 10 Santri Terbaik</span></div>'))
            ->columns([
                Split::make([
                    Tables\Columns\TextColumn::make('index')
                        ->state(function ($record, \Filament\Widgets\TableWidget $livewire) {
                            return $livewire->getTableRecords()->search(fn ($item) => $item->id === $record->id) + 1;
                        })
                        ->label('')
                        ->grow(false)
                        ->formatStateUsing(function ($state) {
                            $colors = match(true) {
                                $state === 1 => 'background: linear-gradient(135deg, #fef08a, #fbbf24); color: #92400e; box-shadow: 0 2px 8px rgba(251,191,36,0.3);',
                                $state === 2 => 'background: linear-gradient(135deg, #e5e7eb, #d1d5db); color: #4b5563;',
                                $state === 3 => 'background: linear-gradient(135deg, #fed7aa, #fdba74); color: #9a3412;',
                                default => 'background: #f3f4f6; color: #6b7280;',
                            };
                            return new \Illuminate\Support\HtmlString('<div style="width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; ' . $colors . '">' . $state . '</div>');
                        }),
                    Tables\Columns\TextColumn::make('nama_santri')
                        ->label('')
                            ->formatStateUsing(function (string $state, $record) {
                                $ustadzName = ($record->kelasHalaqah && $record->kelasHalaqah->ustadz) ? $record->kelasHalaqah->ustadz->nama_ustadz : '-';
                                $kelasName = $record->kelasHalaqah ? $record->kelasHalaqah->nama_kelas : '-';
                                return new \Illuminate\Support\HtmlString('<div style="display: flex; flex-direction: column;">
                                            <span style="font-weight: 600;">' . e($record->nama_santri) . '</span>
                                            <span style="font-size: 0.8rem; color: #9ca3af;">Kelas ' . e($kelasName) . ' &bull; Ust. ' . e($ustadzName) . '</span>
                                        </div>');
                            }),
                    Tables\Columns\TextColumn::make('total_baris')
                        ->label('')
                        ->grow(false)
                        ->formatStateUsing(function ($state) {
                            $totalBaris = (int) $state;
                            $juz = floor($totalBaris / 300);
                            $sisa = $totalBaris % 300;
                            $halaman = floor($sisa / 15);
                            $baris = $sisa % 15;

                            $text = [];
                            if ($juz > 0) $text[] = "{$juz} Juz";
                            if ($halaman > 0) $text[] = "{$halaman} Hal";
                            if ($baris > 0 || empty($text)) $text[] = "{$baris} Brs";

                            $string = implode(', ', $text);
                            return new \Illuminate\Support\HtmlString('<span style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(6,182,212,0.1)); color: #059669; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 700; font-size: 0.8rem; white-space: nowrap;">' . $string . '</span>');
                        }),
                ])->from('md'),
            ])->paginated(false);
    }
}