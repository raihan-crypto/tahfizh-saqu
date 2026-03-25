<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Setting;

class Pengaturan extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $slug = 'pengaturan';
    protected static ?int $navigationSort = 5;
    protected string $view = 'filament.pages.pengaturan';

    public static function canAccess(): bool
    {
        return true;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $namaPesantren = Setting::where('key', 'nama_pesantren')->value('value') ?? 'SD Tahfizh SaQu UMA';
        $logoPesantren = Setting::where('key', 'logo_pesantren')->value('value');

        // FileUpload disk('public') mengharapkan array [ path => path ]
        $logoArray = $logoPesantren ? [$logoPesantren] : [];

        $this->data = [
            'nama_pesantren' => $namaPesantren,
            'logo_pesantren' => $logoArray,
            'tema' => auth()->user()->theme_color ?? 'Amber',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Aplikasi')
                    ->description('Hanya dapat diubah oleh Administrator')
                    ->visible(fn () => auth()->user()->role === 'admin') 
                    ->schema([
                        TextInput::make('nama_pesantren')
                            ->label('Nama Pesantren / Sekolah')
                            ->required(),
                        FileUpload::make('logo_pesantren')
                            ->label('Logo')
                            ->image()
                            ->maxSize(2048)  // Limit 2 MB logic
                            ->disk('public') // PASTIKAN tersimpan di disk public agar bisa di-load browser
                            ->helperText('Maksimal ukuran file: 2 MB.')
                            ->directory('logos'),
                    ])->columns(2),

                Section::make('Tampilan Pribadi')
                    ->description('Warna tema ini hanya akan berlaku untuk akun Anda sendiri.')
                    ->schema([
                        Select::make('tema')
                            ->label('Warna Tema Utama')
                            ->options([
                                'Amber'  => '🟡 Amber (Default)',
                                'Blue'   => '🔵 Blue',
                                'Green'  => '🟢 Green',
                                'Purple' => '🟣 Purple',
                                'Rose'   => '🌸 Rose',
                                'Slate'  => '⚫ Slate',
                            ])
                            ->default('Amber')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (auth()->user()->role === 'admin') {
            $logo = $data['logo_pesantren'] ?? null;
            if (is_array($logo)) {
                $logo = reset($logo);
            }

            Setting::updateOrCreate(['key' => 'nama_pesantren'], ['value' => $data['nama_pesantren'] ?? 'SD Tahfizh SaQu UMA']);
            Setting::updateOrCreate(['key' => 'logo_pesantren'], ['value' => $logo]);
        }

        auth()->user()->update([
            'theme_color' => $data['tema']
        ]);

        Notification::make()->title('Pengaturan berhasil disimpan!')->success()->send();
        
        redirect(request()->header('Referer')); 
    }
}
