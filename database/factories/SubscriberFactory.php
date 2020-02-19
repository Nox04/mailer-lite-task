<?php

/** @var Factory $factory */

use App\Enums\SubscriberState;
use App\Models\Subscriber;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Subscriber::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'state' => $faker->randomElement(SubscriberState::getNames()),
    ];
});
