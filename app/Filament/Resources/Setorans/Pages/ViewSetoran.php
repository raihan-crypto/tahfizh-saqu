<?php

namespace App\Filament\Resources\Setorans\Pages;

use App\Filament\Resources\Setorans\SetoranResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSetoran extends ViewRecord
{
    protected static string $resource = SetoranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
