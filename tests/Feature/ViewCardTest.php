<?php

namespace Tests\Feature;

use App\Game;
use App\Card;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_see_basic_card_details()
    {
        $game = factory(Game::class)->create([
            'name' => 'Game Name',
        ]);

        $card = factory(Card::class)->create([
            'market_hash_name' => '123456-Card%20Name',
            'name' => 'Card Name',
            'game_id' => $game->id,
        ]);

        $response = $this->get('/cards/123456-Card%20Name');

        $response->assertSee('Card Name');
        $response->assertSee('Game Name');
    }
}
