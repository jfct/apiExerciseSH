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

$factory->define(App\Models\Users::class, function (Faker\Generator $faker) {
    $id             = $faker->numberBetween(1, 99);
    $name           = $faker->randomElement(['test', 'test2']);
    $deleted        = 0;
    gc_collect_cycles();

    return [
        'id'                => $id,
        'name'              => str_replace('.', '', $faker->unique()->userName),
        'deleted'           => $deleted
    ];
});
