<?php

namespace App\Filament\Resources\Setorans\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;

class SetoranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Utama')
                    ->schema([
                        Select::make('santri_id')
                            ->relationship('santri', 'nama_santri')
                            ->label('Santri')
                            ->searchable()
                            ->required(),
                        DatePicker::make('tanggal')
                            ->default(now())
                            ->required(),
                        Select::make('kehadiran')
                            ->options([
                                'Hadir'     => 'Hadir',
                                'Izin'      => 'Izin',
                                'Terlambat' => 'Terlambat',
                                'Alpha'     => 'Alpha',
                                'Sakit'     => 'Sakit',
                            ])
                            ->default('Hadir')
                            ->live()
                            ->required(),
                    ])->columns(3),

                Tabs::make('Rincian Setoran')
                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('kehadiran'), ['Hadir', 'Terlambat']))
                    ->tabs([
                        Tabs\Tab::make('Ziyadah')->schema(self::getSetoranFields('ziyadah')),
                        Tabs\Tab::make('Rabth')->schema(self::getSetoranFields('rabth')),
                        Tabs\Tab::make("Muraja'ah")->schema(self::getSetoranFields('murajaah')),
                    ]),

                Section::make('Penilaian')->schema([
                    TextInput::make('nilai_kelancaran')
                        ->numeric()
                        ->default(100)
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('kehadiran'), ['Hadir', 'Terlambat']))
                        ->suffixActions([
                            Action::make('kurangi_5')
                                ->icon('heroicon-m-minus')
                                ->color('danger')
                                ->action(function ($set, $state) {
                                    $set('nilai_kelancaran', max(0, (int)$state - 5));
                                })
                                ->tooltip('Kurangi 5 Poin'),
                            Action::make('reset')
                                ->icon('heroicon-m-arrow-path')
                                ->color('warning')
                                ->action(function ($set) {
                                    $set('nilai_kelancaran', 100);
                                })
                                ->tooltip('Reset ke 100'),
                        ]),
                    Textarea::make('catatan')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    private static function getSetoranFields(string $prefix): array
    {
        $juzOptions = array_combine(range(1, 30), range(1, 30));
        $suratOptions = [
            '1'   => 'Al-Fatihah',   '2'  => 'Al-Baqarah',  '3'   => 'Ali Imran',
            '4'   => 'An-Nisa',      '5'  => 'Al-Maidah',   '6'   => 'Al-Anam',
            '7'   => 'Al-Araf',      '8'  => 'Al-Anfal',    '9'   => 'At-Taubah',
            '10'  => 'Yunus',        '11' => 'Hud',          '12'  => 'Yusuf',
            '13'  => 'Ar-Rad',       '14' => 'Ibrahim',      '15'  => 'Al-Hijr',
            '16'  => 'An-Nahl',      '17' => 'Al-Isra',      '18'  => 'Al-Kahf',
            '19'  => 'Maryam',       '20' => 'Ta-Ha',        '21'  => 'Al-Anbiya',
            '22'  => 'Al-Haj',       '23' => 'Al-Muminun',  '24'  => 'An-Nur',
            '25'  => 'Al-Furqan',    '26' => 'Asy-Syuara',  '27'  => 'An-Naml',
            '28'  => 'Al-Qasas',     '29' => 'Al-Ankabut',  '30'  => 'Ar-Rum',
            '31'  => 'Luqman',       '32' => 'As-Sajdah',   '33'  => 'Al-Ahzab',
            '34'  => 'Saba',         '35' => 'Fatir',        '36'  => 'Yasin',
            '37'  => 'As-Saffat',    '38' => 'Sad',          '39'  => 'Az-Zumar',
            '40'  => 'Gafir',        '67' => 'Al-Mulk',     '68'  => 'Al-Qalam',
            '78'  => 'An-Naba',      '79' => 'An-Naziat',   '80'  => 'Abasa',
            '112' => 'Al-Ikhlas',   '113' => 'Al-Falaq',   '114' => 'An-Nas',
        ];

        return [
            Select::make("{$prefix}_juz")
                ->label('Juz')
                ->options($juzOptions)
                ->searchable(),
            Select::make("{$prefix}_surat")
                ->label('Surat')
                ->options($suratOptions)
                ->searchable(),
            TextInput::make("{$prefix}_ayat_mulai")
                ->label('Ayat Mulai')
                ->numeric(),
            TextInput::make("{$prefix}_ayat_selesai")
                ->label('Ayat Selesai')
                ->numeric(),
            TextInput::make("{$prefix}_baris")
                ->label('Jumlah Baris')
                ->numeric(),
        ];
    }
}
