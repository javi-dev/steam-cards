<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    
    public function booster()
    {
        return $this->hasOne(Booster::class);
    }
}
