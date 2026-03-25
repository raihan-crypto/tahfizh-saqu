<?php

namespace App\Filament\Resources\KelasHalaqahs\Pages;

use App\Filament\Resources\KelasHalaqahs\KelasHalaqahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelasHalaqah extends EditRecord
{
    protected static string $resource = KelasHalaqahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
