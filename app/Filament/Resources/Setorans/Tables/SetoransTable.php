<?php

namespace App\Filament\Resources\Setorans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class SetoransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('tanggal')->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('santri.nama_santri')->label('Santri')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('santri.kelas')->label('Kelas')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('kehadiran')->badge(),
                \Filament\Tables\Columns\TextColumn::make('nilai_kelancaran')->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('tanggal')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('dari_tanggal'),
                        \Filament\Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
                \Filament\Tables\Filters\SelectFilter::make('kelas_santri')
                    ->label('Kelas Santri')
                    ->options(fn () => \App\Models\Santri::query()->select('kelas')->distinct()->pluck('kelas', 'kelas')->toArray())
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('santri', function ($q) use ($data) {
                                $q->where('kelas', $data['value']);
                            });
                        }
                    }),
                \Filament\Tables\Filters\SelectFilter::make('ustadz_pengampu')
                    ->label('Ustadz Pengampu')
                    ->options(fn() => \App\Models\Ustadz::pluck('nama_ustadz', 'id')->toArray())
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('santri', function ($q) use ($data) {
                                $q->where('ustadz_id', $data['value']);
                            });
                        }
                    })
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
