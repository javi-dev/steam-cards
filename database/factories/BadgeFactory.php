<?php

use Faker\Generator as Faker;

$factory->define(App\Badge::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterCreating(App\Badge::class, function ($badge, $faker) {
    $badge->game->booster->crafting_gems = $badge->game->booster->crafting_gems ?? $faker->numberBetween(400, 1200);

    $badge->game->booster->save();
});
