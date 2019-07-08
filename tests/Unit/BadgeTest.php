<?php

namespace Tests\Unit;

use App\Game;
use App\Badge;
use App\Booster;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_badge_always_has_an_associated_booster()
    {
        $badge = factory(Badge::class)->create([
            'game_id' => factory(Game::class),
        ]);

        $this->assertInstanceOf(Booster::class, $badge->game->booster);
    }
}
