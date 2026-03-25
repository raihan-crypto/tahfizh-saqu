<?php

namespace App\Filament\Resources\Ustadzs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class UstadzsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('nama_ustadz')
                    ->label('Nama Ustadz')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P'),
                \Filament\Tables\Columns\TextColumn::make('no_wa')
                    ->label('No WA'),
                \Filament\Tables\Columns\TextColumn::make('asal_pondok')
                    ->label('Asal Pondok'),
                \Filament\Tables\Columns\TextColumn::make('kelas_diampu')
                    ->label('Kelas Diampu')
                    ->state(function (\App\Models\Ustadz $record) {
                        return $record->kelasHalaqahs()->pluck('nama_kelas')->filter()->unique()->sortBy('nama_kelas')->values()->toArray();
                    })
                    ->badge()
                    ->color('success')
                    ->separator(','),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('kelas_halaqah_id')
                    ->label('Kelas')
                    ->options(fn () => \App\Models\KelasHalaqah::pluck('nama_kelas', 'id')->toArray())
                    ->query(function ($query, $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('kelasHalaqahs', function ($q) use ($data) {
                                $q->where('id', $data['value']);
                            });
                        }
                    }),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
