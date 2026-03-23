<?php

namespace App\Filament\Resources\Setorans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SetoranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('santri_id')
                    ->numeric(),
                TextEntry::make('ustadz_id')
                    ->numeric(),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('jenis_setoran')
                    ->badge(),
                TextEntry::make('juz')
                    ->numeric(),
                TextEntry::make('nama_surat'),
                TextEntry::make('ayat_mulai')
                    ->numeric(),
                TextEntry::make('ayat_selesai')
                    ->numeric(),
                TextEntry::make('jumlah_baris')
                    ->numeric(),
                TextEntry::make('kehadiran')
                    ->badge(),
                TextEntry::make('nilai_kelancaran')
                    ->numeric(),
                TextEntry::make('catatan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
