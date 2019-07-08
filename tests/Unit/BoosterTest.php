<?php

namespace Tests\Unit;

use App\Offer;
use App\Booster;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\FluentFactories\GameFactory;

class BoosterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_booster_can_have_offers()
    {
        $booster = factory(Booster::class)->create();

        $offer = factory(Offer::class)->create([
            'booster_id' => $booster->game->id,
            'price' => 35,
            'quantity' => 4,
        ]);


        $this->assertEquals(35, $offer->price);
        $this->assertEquals(4, $offer->quantity);
        $this->assertEquals(35, $booster->offers->pluck('price')->first());
    }

    /** @test */
    function a_booster_can_calculate_profits_when_undercutting()
    {
        $sack_of_gems_price = 24; //Temporary

        $game = app(GameFactory::class)->withBadges()->overrides(['booster_crafting_gems' => 1000])->create()->first();

        $midOffers = factory(Offer::class)->create([
            'booster_id' => $game->booster->id,
            'price' => 37,
            'quantity' => 1,
        ]);

        $lowOffers = factory(Offer::class)->create([
            'booster_id' => $game->booster->id,
            'price' => 35,
            'quantity' => 4,
        ]);

        $hiOffers = factory(Offer::class)->create([
            'booster_id' => $game->booster->id,
            'price' => 41,
            'quantity' => 1,
        ]);

        $this->assertEquals(10, $game->booster->undercut_profit);
    }
}
