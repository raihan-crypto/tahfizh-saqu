<?php

namespace App\Filament\Resources\Ustadzs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UstadzInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_ustadz'),
                TextEntry::make('jenis_kelamin')
                    ->badge(),
                TextEntry::make('no_wa'),
                TextEntry::make('asal_pondok'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
