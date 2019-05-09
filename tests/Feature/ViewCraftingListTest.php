<?php

namespace Tests\Feature;

use App\Game;
use App\User;
use App\Booster;
use Tests\TestCase;
use PHPUnit\Framework\Assert;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCraftingListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Collection::macro('assertEquals', function ($items) {
            Assert::assertEquals(count($this), count($items));
            $this->zip($items)->each(function ($pair) {
                list($a, $b) = $pair;
                Assert::assertTrue($a->is($b));
            });
        });
    }

    private function boostersforUser($user, $quantity)
    {
        return factory(Booster::class, $quantity)->create()->each(function ($booster) use ($user) {
            $booster->game->user()->save($user);
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
        $boosters = $this->boostersforUser($user, 3);
        $otherGame = factory(Game::class)->create();
        $user->games()->save($otherGame);

        $response = $this->actingAs($user)->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->assertEquals($boosters->pluck('game'));
        $boosters->each(function($game) use ($response) {
            $response->assertSee($game->name);
        });
        $response->assertDontSee($otherGame->name);
        
    }

    /** @test */
    function users_can_only_view_a_list_of_their_games()
    {
        $user = factory(User::class)->create();
        $boosters = $this->boostersforUser($user, 3);
        $otherGameBooster = factory(Booster::class)->create();

        $response = $this->actingAs($user)->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->assertEquals($boosters->pluck('game'));
        $boosters->each(function($game) use ($response) {
            $response->assertSee($game->name);
        });
        $response->assertDontSee($otherGameBooster->game->name);
    }

    /** @test */
    function users_can_see_the_booster_crafting_cost_of_their_games()
    {
        $user = factory(User::class)->create();
        $boosters = $this->boostersforUser($user, 3);
        
        $response = $this->actingAs($user)->get("/{$user->name}/games/crafting");

        $response->original->getData()['games']->pluck('booster')->assertEquals($boosters);

        $boosters->each(function($booster) use ($response) {
            $response->assertSee($booster->crafting_gems);
            $response->assertSee($booster->game->name);
        });
    }


}
