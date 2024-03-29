<?php

namespace Tests\Feature;

use App\Game;
use App\User;
use App\Badge;
use App\Offer;
use App\Booster;
use Tests\TestCase;
use Database\FluentFactories\GameFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCraftingListTest extends TestCase
{
    use RefreshDatabase;

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
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $gamesWithBadges = app(GameFactory::class)->ownedBy($user)->withBadges()->create(3);
        $gamesWithoutBadges = app(GameFactory::class)->ownedBy($user)->withBadges(false)->create(3);

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
        $userGamesWithBadges = app(GameFactory::class)->withBadges()->create(3);
        $anotherGame = app(GameFactory::class)->withBadges()->create()->first();

        $response = $this->actingAs($user = $userGamesWithBadges[0]->user[0])->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->assertEquals($userGamesWithBadges);
        $userGamesWithBadges->each(function ($game) use ($response) {
            $response->assertSee($game->name);
        });
        $response->assertDontSee($anotherGame->name);
    }

    /** @test */
    function users_can_see_the_booster_crafting_cost_of_their_games()
    {
        $games = app(GameFactory::class)->withBadges()->create(3);

        $response = $this->actingAs($user = $games[0]->user[0])->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->assertEquals($games);

        $user->gamesWithBadges->each(function ($game) use ($response) {
            $this->assertNotNull($game->booster->crafting_gems);
            $response->assertSee($game->booster->crafting_gems);
            $response->assertSee($game->name);
        });
    }

    /** @test */
    function users_can_see_the_expected_earnings_when_undercutting_boosters()
    {
        $sack_of_gems_price = 24;

        $game = app(GameFactory::class)->withBadges()->craftingGems(1000)->create()->first();

        $offers = collect([
            34 => 1,
            36 => 4,
            41 => 1,
            50 => 1,
            57 => 1,
        ]);

        $offers->each(function ($quantity, $price) use ($game) {
            factory(Offer::class)->create([
                'booster_id' => $game->booster->id,
                'price' => $price,
                'quantity' => $quantity,
            ]);
        });

        $response = $this->actingAs($user = $game->user[0])->get("/{$user->name}/games/crafting")->assertSuccessful();

        $response->assertSee('0.09 €');
    }
}
