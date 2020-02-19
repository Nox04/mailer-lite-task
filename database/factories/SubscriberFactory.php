<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\SubscriberState;
use App\Models\Subscriber;
use Faker\Generator as Faker;

$factory->define(Subscriber::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'state' => $faker->randomElement(SubscriberState::getNames()),
    ];
});
