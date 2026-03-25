<?php

namespace App\Filament\Resources\Santris\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SantriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nisn'),
                TextEntry::make('nama_santri'),
                TextEntry::make('jenis_kelamin')
                    ->badge(),
                TextEntry::make('kelasHalaqah.nama_kelas')->label('Kelas'),
                TextEntry::make('kelasHalaqah.ustadz.nama_ustadz')->label('Guru Pengampu'),
                TextEntry::make('nama_orangtua'),
                TextEntry::make('wa_orangtua'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
