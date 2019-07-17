<?php

use App\Offer;
use App\Booster;
use Faker\Generator as Faker;

$factory->define(App\Offer::class, function (Faker $faker) {
    return [
        'price' => $faker->numberBetween(1, 499),
        'quantity' => $faker->numberBetween(1, 99),
    ];
});
