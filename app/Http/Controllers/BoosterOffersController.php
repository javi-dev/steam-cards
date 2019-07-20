<?php

namespace App\Http\Controllers;

use App\Booster;
use Illuminate\Http\Request;
use App\Steam\Community\Market;
use App\Http\Controllers\Controller;
use App\Steam\Community\Market\FakeMarket;

class BoosterOffersController extends Controller
{
    public function update($booster)
    {
        $booster = Booster::findOrFail($booster);

        $market = app(Market::class);

        $market->getOffers($booster);
    }
}
