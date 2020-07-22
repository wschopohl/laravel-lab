<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'category_id' => $faker->numberBetween(1261,2540),
        'name' => $faker->lastName
    ];
});
