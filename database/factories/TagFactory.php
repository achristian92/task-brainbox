<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Repositories\Tags\Tag::class, function (Faker $faker) {

    $name = $faker->unique()->randomElement([
        'Sunat',
        'Planilla',
        'Egreso',
        'Ingreso',
        'Libre',
        'Vacaciones'
    ]);

    $color = $faker->unique()->randomElement([
        '#11cdef',
        '#8898aa',
        '#5e72e4',
        '#f5365c',
        '#2dce89',
        '#172b4d',
    ]);

    return [
        'name' => $name,
        'status' => 1,
        'color' => $color
    ];
});
