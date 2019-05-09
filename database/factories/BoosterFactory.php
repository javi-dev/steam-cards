<?php

use Faker\Generator as Faker;

$factory->define(App\Booster::class, function (Faker $faker) {
    return [
        'game_id' => factory(App\Game::class)->create(),
        'crafting_gems' => $faker->numberBetween(400, 1200),
    ];
});
