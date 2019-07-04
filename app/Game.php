<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = null;

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function badge()
    {
        return $this->hasOne(Badge::class);
    }

    public function booster()
    {
        return $this->hasOne(Booster::class);
    }
}
