<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Santri extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function kelasHalaqah()
    {
        return $this->belongsTo(KelasHalaqah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setorans()
    {
        return $this->hasMany(Setoran::class);
    }
}
