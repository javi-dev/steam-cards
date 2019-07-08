<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booster extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function getUndercutProfitAttribute()
    {
        if ($this->offers->isEmpty()) {
            return 'No offers';
        }

        $sack_of_gems_price = 24; // TODO

        $booster_cost = $this->game->booster_crafting_gems * $sack_of_gems_price / 1000;

        $undercut_price = $this->offers()->get()->sortBy('price')->first()->price - 1;

        return $undercut_price - $booster_cost;
    }
}
