<?php

namespace App\Filament\Resources\Setorans;

use App\Filament\Resources\Setorans\Pages\CreateSetoran;
use App\Filament\Resources\Setorans\Pages\EditSetoran;
use App\Filament\Resources\Setorans\Pages\ListSetorans;
use App\Filament\Resources\Setorans\Schemas\SetoranForm;
use App\Filament\Resources\Setorans\Tables\SetoransTable;
use App\Models\Setoran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SetoranResource extends Resource
{
    protected static ?string $model = Setoran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'santri_id';

    public static function form(Schema $schema): Schema
    {
        return SetoranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SetoransTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSetorans::route('/'),
            'create' => CreateSetoran::route('/create'),
            'edit' => EditSetoran::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->role === 'wali_murid') {
            $query->whereHas('santri', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }
        return $query;
    }
}
