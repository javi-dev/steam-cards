<?php

namespace Tests\Feature;

use App\Booster;
use Tests\TestCase;
use App\Steam\Community\Market;
use App\Steam\Community\FakeMarket;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateOffersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function booster_offers_can_be_updated_from_the_market()
    {
        app()->bind(Market::class, FakeMarket::class);
        $booster = factory(Booster::class)->create();
        $oldOffers = $booster->offers;

        $this->put("/booster/{$booster->id}/offers");

        $this->assertNotEquals($oldOffers, $booster->fresh()->fresh()->offers);
    }

    /** @test */
    function booster_must_exist_to_update_offers()
    {
        app()->bind(Market::class, FakeMarket::class);

        $response = $this->put("/booster/4242/offers");

        $response->assertNotFound();
    }
}
