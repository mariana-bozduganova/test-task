<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Subscription::class, function (Faker\Generator $faker) {
    $gender = $faker->randomElement(['W', 'M']);

    return [
        'id' => $faker->unique()->randomNumber(5),
        'subscriber_id' => $faker->unique()->randomNumber(5),
        'gender' => $gender,
        'size' => $gender == 'W' ? $faker->randomElement(config('runrepeat.sizes.W')) : $faker->randomElement(config('runrepeat.sizes.M')),
        'status' => 'active'
    ];
});
