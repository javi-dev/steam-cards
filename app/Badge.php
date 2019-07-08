<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    public static function boot()
    {
        parent::boot();

        self::creating(function ($badge) {
            $badge->game->booster()->save(new Booster);
        });
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
