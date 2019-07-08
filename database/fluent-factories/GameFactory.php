<?php

namespace Database\FluentFactories;

use App\Game;
use App\User;
use App\Badge;

class GameFactory
{
    protected $user = null;

    protected $withBadges;

    protected $overrides = [];

    public function ownedBy(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function withBadges($withBadges = true)
    {
        $this->withBadges = $withBadges;

        return $this;
    }

    public function overrides($overrides)
    {
        $this->overrides = $overrides;

        return $this;
    }

    public function create($count = 1)
    {
        $games = factory(Game::class, $count)->create($this->overrides);

        if ($this->withBadges) {
            $games->each(function ($game) {
                $game->badge = factory(Badge::class)->create([
                    'game_id' => $game->id
                ]);
            });
        }

        $this->user = $this->user ?? factory(User::class)->create();

        $games->each(function ($game) {
            $game->user()->attach($this->user);
        });

        return $games;
    }
}
