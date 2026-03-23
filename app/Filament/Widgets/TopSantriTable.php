<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopSantriTable extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'half';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Santri::query()
                    ->selectRaw('santris.*, (SELECT COALESCE(SUM(ziyadah_baris), 0) FROM setorans WHERE setorans.santri_id = santris.id) as total_baris')
                    ->orderByDesc('total_baris')
                    ->limit(10)
            )
            ->heading('Top 10 Santri Hafalan Terbaik')
            ->columns([
                Tables\Columns\TextColumn::make('nama_santri')->label('Nama'),
                Tables\Columns\TextColumn::make('total_baris')
                    ->label('Pencapaian')
                    ->formatStateUsing(function ($state) {
                        $totalBaris = (int) $state;
                        $juz = floor($totalBaris / 300); // 300 baris per juz
                        $sisa = $totalBaris % 300;
                        $halaman = floor($sisa / 15); // 15 baris per halaman
                        $baris = $sisa % 15;

                        $text = [];
                        if ($juz > 0) $text[] = "{$juz} juz";
                        if ($halaman > 0) $text[] = "{$halaman} hal";
                        if ($baris > 0 || empty($text)) $text[] = "{$baris} baris";

                        return implode(', ', $text);
                    }),
            ])->paginated(false);
    }
}