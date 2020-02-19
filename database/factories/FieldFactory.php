<?php

/** @var Factory $factory */

use App\Enums\FieldType;
use App\Models\Field;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Field::class, function (Faker $faker) {
    return [
        'title' => ucfirst($faker->word),
        'type' => $faker->randomElement(FieldType::getNames()),
    ];
});
