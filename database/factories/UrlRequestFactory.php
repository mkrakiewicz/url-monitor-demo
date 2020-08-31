<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UrlRequest;
use App\User;
use Faker\Generator as Faker;

$factory->define(UrlRequest::class, function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['pending', 'completed']),
//        'user_id' => factory(User::class)
    ];
});


$factory->state(UrlRequest::class, 'demo', function (Faker $faker) {
    return [
//        'user_id' => function () {
//            return User::inRandomOrder()->first();
//        },
        'created_at' => $faker->dateTimeBetween('-20 minutes')
    ];
});
