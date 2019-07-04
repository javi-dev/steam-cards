<?php

use Faker\Generator as Faker;

$factory->define(App\Badge::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterCreating(App\Badge::class, function ($badge, $faker) {
    $badge->game->booster_crafting_gems = $faker->numberBetween(400, 1200);

    $badge->game->save();
});
