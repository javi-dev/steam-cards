<?php

use Faker\Generator as Faker;

$factory->define(App\Booster::class, function (Faker $faker) {
    return [
        'game_id' => function () {
            return factory(App\Game::class)->create();
        }
    ];
});

$factory->afterCreating(App\Booster::class, function ($booster, $faker) {
    $booster->game->booster_crafting_gems = $faker->numberBetween(400, 1200);

    $booster->game->save();
});
