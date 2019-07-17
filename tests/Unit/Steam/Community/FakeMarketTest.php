<?php

namespace Tests\Unit\Steam\Community;

use App\Booster;
use Tests\TestCase;
use App\Steam\Community\FakeMarket;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FakeMarketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_get_the_offers_for_a_booster()
    {
        $market = new FakeMarket;
        $booster = factory(Booster::class)->create();
        $this->assertEmpty($booster->offers);

        $market->getOffers($booster);

        $this->assertNotEmpty($booster->fresh()->offers);
    }
}
