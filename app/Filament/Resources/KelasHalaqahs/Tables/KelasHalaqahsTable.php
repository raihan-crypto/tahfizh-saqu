<?php

namespace App\Filament\Resources\KelasHalaqahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class KelasHalaqahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Kelas / Halaqah')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('ustadz.nama_ustadz')
                    ->label('Guru Pengampu')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('santris_count')
                    ->label('Jumlah Santri')
                    ->counts('santris'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
