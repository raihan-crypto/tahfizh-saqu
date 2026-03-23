<?php

namespace App\Filament\Resources\Ustadzs\Schemas;

use Filament\Schemas\Schema;

class UstadzForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('nama_ustadz')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('no_wa')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('asal_pondok')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
