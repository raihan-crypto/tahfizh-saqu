<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'theme_color',
        'kelas_tingkat',
        'ustadz_id',
    ];

    public function santris()
    {
        return $this->hasMany(Santri::class);
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    /**
     * Get KelasHalaqah IDs for guru user (via ustadz relation)
     */
    public function guruKelasHalaqahIds()
    {
        if (!$this->ustadz_id) return collect();
        return KelasHalaqah::where('ustadz_id', $this->ustadz_id)->pluck('id');
    }

    /**
     * Get all KelasHalaqah that belong to this user's kelas_tingkat.
     * e.g. kelas_tingkat=1 returns 1/A, 1/B, 1/C
     */
    public function kelasHalaqahs()
    {
        return KelasHalaqah::where('nama_kelas', 'LIKE', $this->kelas_tingkat . '/%')->get();
    }

    public function kelasHalaqahIds()
    {
        return KelasHalaqah::where('nama_kelas', 'LIKE', $this->kelas_tingkat . '/%')->pluck('id');
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'app') {
            return in_array($this->role, ['admin', 'guru', 'ustadz', 'wali_santri']);
        }

        return true;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
