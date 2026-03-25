<?php

namespace App\Filament\Resources\KelasHalaqahs;

use App\Filament\Resources\KelasHalaqahs\Pages\CreateKelasHalaqah;
use App\Filament\Resources\KelasHalaqahs\Pages\EditKelasHalaqah;
use App\Filament\Resources\KelasHalaqahs\Pages\ListKelasHalaqahs;
use App\Filament\Resources\KelasHalaqahs\Schemas\KelasHalaqahForm;
use App\Filament\Resources\KelasHalaqahs\Tables\KelasHalaqahsTable;
use App\Models\KelasHalaqah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KelasHalaqahResource extends Resource
{
    protected static ?string $model = KelasHalaqah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_kelas';
    protected static ?string $navigationLabel = 'Data Kelas / Halaqah';
    protected static ?string $slug = 'data-kelas-halaqah';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return KelasHalaqahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelasHalaqahsTable::configure($table);
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
            'index' => ListKelasHalaqahs::route('/'),
            'create' => CreateKelasHalaqah::route('/create'),
            'edit' => EditKelasHalaqah::route('/{record}/edit'),
        ];
    }
}
