<?php

namespace Tests\Unit;

use App\Game;
use App\Booster;
use Tests\TestCase;
use PHPUnit\Framework\Assert;
use Database\FluentFactories\GameFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function games_without_badges_dont_have_a_booster_crafting_cost()
    {
        $game = factory(Game::class)->create();

        $this->assertNull($game->booster_crafting_gems);
    }

    /** @test */
    function games_with_badges_have_a_booster_crafting_cost()
    {
        $game = app(GameFactory::class)->withBadges()->create()->first();

        $this->assertNotNull($game->fresh()->booster_crafting_gems);
    }

    /** @test */
    function games_can_have_boosters()
    {
        $game = app(GameFactory::class)->withBadges()->create()->first();
        $booster = factory(Booster::class)->create([
            'game_id' => $game->id,
        ]);

        $this->assertInstanceOf(Booster::class, $game->booster);
    }
}
