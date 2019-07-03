<?php

namespace Tests\Unit;

use App\Game;
use App\User;
use App\Badge;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function users_can_get_all_their_badges()
    {
        $user = factory(User::class)->create();
        $gamesWithBadges = factory(Game::class, 3)->create()->each(function ($game) {
            factory(Badge::class)->create(['game_id' => $game->id]);
        });
        $gameWithNoBadge = factory(Game::class)->create();
        $user->games()->saveMany($gamesWithBadges);
        $user->games()->save($gameWithNoBadge);

        $gamesWithBadges->pluck('badge')->assertEquals($user->badges);
    }

    /** @test */
    function users_can_get_their_games_with_badges()
    {
        $user = factory(User::class)->create();
        $gamesWithBadges = factory(Game::class, 3)->create()->each(function ($game) {
            $game->badge()->save(factory(Badge::class)->create(['game_id' => $game->id]));
        });
        $gameWithNoBadge = factory(Game::class)->create();
        $user->games()->saveMany($gamesWithBadges);
        $user->games()->save($gameWithNoBadge);

        $gamesWithBadges->assertEquals($user->gamesWithBadges);
    }
}
