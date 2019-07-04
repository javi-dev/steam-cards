<?php

use Faker\Generator as Faker;

$factory->define(App\Booster::class, function (Faker $faker) {
    return [
        'game_id' => function () {
            return factory(App\Game::class)->create();
        }
    ];
});
