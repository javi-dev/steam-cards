<?php

namespace Tests\Feature;

use App\Game;
use App\User;
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

    /** @test */
    function guests_cannot_view_users_games()
    {
        $user = factory(User::class)->create();

        $response = $this->get("/{$user->name}/games");

        $response->assertRedirect('/login');
    }

    /** @test */
    function user_can_only_view_a_list_of_his_games()
    {
        $user = factory(User::class)->create();
        $games = factory(Game::class, 2)->create();
        $user->games()->saveMany($games);
        $otherGame = factory(Game::class)->create();

        $response = $this->actingAs($user)->get("/{$user->name}/games");

        $response->original->getData()['games']->assertEquals($games);
        $games->each(function($game) use ($response) {
            $response->assertSee($game->name);
        });
        $response->assertDontSee($otherGame);
    }
}
