<?php

namespace App\Filament\Resources\Santris\Schemas;

use Filament\Schemas\Schema;

class SantriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('nisn')
                    ->label('NISN')
                    ->required()
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\TextInput::make('nama_santri')
                    ->required(),
                \Filament\Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('kelas')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('kelas_halaqah')
                    ->required(),
                \Filament\Forms\Components\Select::make('ustadz_id')
                    ->relationship('ustadz', 'nama_ustadz')
                    ->required(),
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name', fn($query) => $query->where('role', 'wali_murid'))
                    ->label('Akun Wali Murid')
                    ->nullable(),
                \Filament\Forms\Components\TextInput::make('nama_orangtua')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('wa_orangtua')
                    ->tel()
                    ->required(),
            ]);
    }
}
