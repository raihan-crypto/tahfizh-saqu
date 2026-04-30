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
                \Filament\Tables\Columns\TextColumn::make('santri.kelasHalaqah.nama_kelas')->label('Kelas')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('kehadiran')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Terlambat' => 'warning',
                        'Izin', 'Sakit' => 'info',
                        'Alpha' => 'danger',
                        default => 'gray',
                    }),
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
                    ->label('Kelas')
                    ->options(fn () => \App\Models\KelasHalaqah::pluck('nama_kelas', 'id')->toArray())
                    ->query(function ($query, $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('santri', function ($q) use ($data) {
                                $q->where('kelas_halaqah_id', $data['value']);
                            });
                        }
                    }),
                \Filament\Tables\Filters\SelectFilter::make('ustadz_pengampu')
                    ->label('Ustadz Pengampu')
                    ->options(fn() => \App\Models\Ustadz::pluck('nama_ustadz', 'id')->toArray())
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('santri', function ($q) use ($data) {
                                $q->whereHas('kelasHalaqah', function ($q2) use ($data) {
                                    $q2->where('ustadz_id', $data['value']);
                                });
                            });
                        }
                    })
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
