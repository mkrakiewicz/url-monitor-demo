<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UrlRequest;
use Faker\Generator as Faker;

$factory->define(UrlRequest::class, function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['pending', 'completed']),
        'created_at' => $faker->dateTimeBetween('-20 minutes')
    ];
});
