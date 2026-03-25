<?php

namespace App\Filament\Resources\KelasHalaqahs\Pages;

use App\Filament\Resources\KelasHalaqahs\KelasHalaqahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKelasHalaqahs extends ListRecords
{
    protected static string $resource = KelasHalaqahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
