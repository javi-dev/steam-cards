<?php

namespace Tests\Feature;

use App\Game;
use App\User;
use App\Badge;
use App\Booster;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCraftingListTest extends TestCase
{
    use RefreshDatabase;

    private function badgesForUser($user, $quantity = 1)
    {
        $games = factory(Game::class, $quantity)->create();

        $user->games()->saveMany($games);

        return $games->map(function ($game) {
            return factory(Badge::class)->create(['game_id' => $game->id]);
        });
    }

    /** @test */
    function guests_cannot_view_users_games()
    {
        $user = factory(User::class)->create();

        $response = $this->get("/{$user->name}/games/crafting");

        $response->assertRedirect('/login');
    }

    /** @test */
    function users_can_only_see_games_they_can_craft_boosters_for()
    {
        $user = factory(User::class)->create();
        $gamesWithBadges = $this->badgesForUser($user, 3)->pluck('game');
        $gamesWithoutBadges = factory(Game::class, 2)->create();
        $user->games()->saveMany($gamesWithoutBadges);

        $response = $this->actingAs($user)->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->assertEquals($gamesWithBadges);
        $gamesWithBadges->each(function ($game) use ($response) {
            $response->assertSee($game->name);
        });
        $gamesWithoutBadges->each(function ($game) use ($response) {
            $response->assertDontSee($game->name);
        });
    }


    /** @test */
    function users_can_only_view_a_list_of_the_games_they_can_create_boosters_for()
    {
        $user = factory(User::class)->create();
        $userGamesWithBadges = $this->badgesForUser($user, 3)->pluck('game');
        $anotherGame = factory(Game::class)->create();
        $anotherBadge = factory(Badge::class)->create(['game_id' => $anotherGame->id]);

        $response = $this->actingAs($user)->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->assertEquals($userGamesWithBadges);
        $userGamesWithBadges->each(function ($game) use ($response) {
            $response->assertSee($game->name);
        });
        $response->assertDontSee($anotherGame->name);
    }

    /** @test */
    function users_can_see_the_booster_crafting_cost_of_their_games()
    {
        $user = factory(User::class)->create();
        $this->badgesForUser($user, 3);
        $user->games->each(function ($game) {
            factory(Booster::class)->create(['game_id' => $game->id]);
        });

        $response = $this->actingAs($user)->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->assertEquals($user->fresh()->gamesWithBadges);

        $user->gamesWithBadges->each(function ($game) use ($response) {
            $this->assertNotNull($game->fresh()->booster_crafting_gems);
            $response->assertSee($game->booster_crafting_gems);
            $response->assertSee($game->name);
        });
    }
}
