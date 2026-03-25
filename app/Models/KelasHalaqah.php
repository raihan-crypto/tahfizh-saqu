<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasHalaqah extends Model
{
    protected $guarded = [];

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function santris()
    {
        return $this->hasMany(Santri::class);
    }
}
