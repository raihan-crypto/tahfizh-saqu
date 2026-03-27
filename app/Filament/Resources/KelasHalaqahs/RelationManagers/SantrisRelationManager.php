<?php

namespace App\Filament\Resources\KelasHalaqahs\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SantrisRelationManager extends RelationManager
{
    protected static string $relationship = 'santris';
    protected static ?string $title = 'Daftar Santri';
    protected static ?string $recordTitleAttribute = 'nama_santri';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->rowIndex()
                    ->label('No'),
                Tables\Columns\TextColumn::make('nama_santri')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_orangtua')
                    ->label('Wali Santri'),
                Tables\Columns\TextColumn::make('total_ziyadah')
                    ->label('Total Ziyadah')
                    ->state(function ($record) {
                        return number_format($record->setorans()->sum('ziyadah_baris'));
                    })
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('total_murajaah')
                    ->label('Total Murajaah')
                    ->state(function ($record) {
                        return number_format($record->setorans()->sum('murajaah_baris'));
                    })
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('nama_santri');
    }
}
