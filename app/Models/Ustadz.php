<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ustadz extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function santris()
    {
        return $this->hasMany(Santri::class);
    }
}
