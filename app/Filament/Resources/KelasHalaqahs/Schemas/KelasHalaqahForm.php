<?php

namespace App\Filament\Resources\KelasHalaqahs\Schemas;

use Filament\Schemas\Schema;

class KelasHalaqahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('nama_kelas')
                    ->label('Nama Kelas / Halaqah')
                    ->placeholder('Contoh: 1/A')
                    ->required(),
                \Filament\Forms\Components\Select::make('ustadz_id')
                    ->relationship('ustadz', 'nama_ustadz')
                    ->label('Guru Pengampu (Wali Kelas)')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
