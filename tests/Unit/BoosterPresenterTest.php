<?php

namespace Tests\Unit;

use App\Offer;
use Tests\TestCase;
use Database\FluentFactories\GameFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BoosterPresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_displays_the_profit_formatted_as_euros()
    {
        $game = app(GameFactory::class)->withBadges()->craftingGems(1000)->create()->first();

        $offer = factory(Offer::class)->create([
            'booster_id' => $game->booster->id,
            'price' => 35,
            'quantity' => 4,
        ]);


        $this->assertEquals('0.10 €', $game->booster->undercut_profit_euro);
    }

    /** @test */
    function a_booster_without_offers_displays_that_instead()
    {
        $game = app(GameFactory::class)->withBadges()->craftingGems(1000)->create()->first();

        $this->assertEquals('No offers', $game->booster->undercut_profit_euro);
    }
}
