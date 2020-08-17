<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UrlRequestStat;
use Faker\Generator as Faker;

$factory->define(UrlRequestStat::class, function (Faker $faker) {
    return [
        'redirects_count' => $faker->numberBetween(0, 15),
        'total_loading_time' => $faker->randomFloat(3, 0, 10),
        'created_at' => $faker->dateTimeBetween('-20 minutes')
    ];
});
