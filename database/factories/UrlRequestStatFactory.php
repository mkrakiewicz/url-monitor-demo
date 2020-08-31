<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UrlRequestStat;
use App\User;
use Faker\Generator as Faker;

$factory->define(UrlRequestStat::class, function (Faker $faker) {
    return [
        'redirects_count' => $faker->numberBetween(0, 15),
        'total_loading_time' => $faker->randomFloat(3, 0, 10),
        'status' => $faker->randomElement([null, 200, 404])
    ];
});


$factory->state(UrlRequestStat::class, 'demo', function (Faker $faker) {
    return [
//        'user_id' => function () {
//            return User::inRandomOrder()->first();
//        },
        'created_at' => $faker->dateTimeBetween('-20 minutes')
    ];
});
