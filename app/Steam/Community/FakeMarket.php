<?php

namespace App\Steam\Community;

use App\Offer;

class FakeMarket implements Market
{
    public function getOffers($booster)
    {
        factory(Offer::class, 3)->create([
            'booster_id' => $booster->id,
        ]);
    }
}
