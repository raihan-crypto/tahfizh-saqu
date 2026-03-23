<?php

namespace App\Filament\Resources\Santris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class SantrisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('nisn')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('nama_santri')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('jenis_kelamin'),
                \Filament\Tables\Columns\TextColumn::make('kelas')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('kelas_halaqah'),
                \Filament\Tables\Columns\TextColumn::make('ustadz.nama_ustadz')->label('Ustadz Pengampu'),
                \Filament\Tables\Columns\TextColumn::make('nama_orangtua'),
                \Filament\Tables\Columns\TextColumn::make('wa_orangtua'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('kelas')
                    ->options(fn () => \App\Models\Santri::query()->select('kelas')->distinct()->pluck('kelas', 'kelas')->toArray()),
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
