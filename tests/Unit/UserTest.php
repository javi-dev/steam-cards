<?php

namespace Tests\Unit;

use App\Game;
use App\User;
use App\Badge;
use Tests\TestCase;
use Database\FluentFactories\GameFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function users_can_get_all_their_badges()
    {
        $user = factory(User::class)->create();
        $gamesWithBadges = app(GameFactory::class)->ownedBy($user)->withBadges()->create(3);
        $gameWithNoBadge = app(GameFactory::class)->ownedBy($user)->create();

        $gamesWithBadges->pluck('badge')->assertEquals($user->badges);
    }

    /** @test */
    function users_can_get_their_games_with_badges()
    {
        $user = factory(User::class)->create();
        $gamesWithBadges = app(GameFactory::class)->ownedBy($user)->withBadges()->create(3);
        $gameWithNoBadge = app(GameFactory::class)->ownedBy($user)->create();

        $gamesWithBadges->assertEquals($user->gamesWithBadges);
    }
}
