<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Split;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class BottomSantriTable extends BaseWidget
{
    protected static ?int $sort = 5;
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
                    ->orderBy('total_baris')
                    ->limit(10)
            )
            ->heading(new \Illuminate\Support\HtmlString('<div style="display: flex; align-items: center; gap: 0.5rem;"><div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #fee2e2, #fecaca); display: flex; align-items: center; justify-content: center;"><svg style="width: 1rem; height: 1rem; color: #e11d48;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div><span style="font-size: 1rem; font-weight: 800; color: #e11d48;">Top 10 Butuh Perhatian</span></div>'))
            ->columns([
                Split::make([
                    Tables\Columns\TextColumn::make('index')
                        ->state(function ($record, BaseWidget $livewire) {
                            return $livewire->getTableRecords()->search(fn ($item) => $item->id === $record->id) + 1;
                        })
                        ->label('')
                        ->grow(false)
                        ->formatStateUsing(function ($state) {
                            $colors = match(true) {
                                $state <= 3 => 'background: linear-gradient(135deg, #fecaca, #fca5a5); color: #991b1b; box-shadow: 0 2px 8px rgba(239,68,68,0.2);',
                                default => 'background: #fee2e2; color: #b91c1c;',
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
                            return new \Illuminate\Support\HtmlString('<span style="background: linear-gradient(135deg, rgba(239,68,68,0.15), rgba(244,63,94,0.1)); color: #e11d48; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 700; font-size: 0.8rem; white-space: nowrap;">' . $string . '</span>');
                        }),
                ])->from('md'),
            ])->paginated(false);
    }
}