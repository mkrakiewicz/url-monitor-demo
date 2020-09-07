<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Url;
use App\User;
use Faker\Generator as Faker;

$factory->define(Url::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
//        'user_id' => factory(User::class)
    ];
});


$factory->state(Url::class, 'demo', function (Faker $faker) {
    return [
//        'user_id' => function () {
//            return User::inRandomOrder()->first();
//        },
        'created_at' => $faker->dateTimeBetween('-20 minutes')
    ];
});
