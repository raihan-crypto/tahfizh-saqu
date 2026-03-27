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

    protected static ?string $modelLabel = 'Input Setoran';
    protected static ?string $pluralModelLabel = 'Input Setoran';
    protected static ?string $navigationLabel = 'Input Setoran';
    protected static ?string $slug = 'input-setoran';
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $recordTitleAttribute = 'santri_id';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'ustadz', 'guru']);
    }

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
        $user = auth()->user();

        if ($user->role === 'guru' && $user->ustadz_id) {
            $kelasIds = $user->guruKelasHalaqahIds();
            $query->whereHas('santri', function ($q) use ($kelasIds) {
                $q->whereIn('kelas_halaqah_id', $kelasIds);
            });
        }

        if ($user->role === 'wali_santri') {
            $kelasIds = $user->kelasHalaqahIds();
            $query->whereHas('santri', function ($q) use ($kelasIds) {
                $q->whereIn('kelas_halaqah_id', $kelasIds);
            });
        }

        return $query;
    }
}
