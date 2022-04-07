<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Repositories\Customers\Customer::class, function (Faker $faker) {
    $name = $faker->unique()->randomElement([
        'Accenture Tecnology',
        'AUTOREL S.R.L.',
        'Energia Azul',
        'Global Backbone',
        'MGA Exportaciones',
        'WHITE LION',
        'SAMAY OPERACIONES',
        'DIGITAL PRO ASESORES',
        'CAPITAL LOGISTIC SAC',
        'CENTAURO',
        'LOGIC MEDIA',
        'LEMATIC S.A.C.',
        'NEGOCIOS Y SERVICIOS INTEGRALES GUADALUPE E.I.R.L.',
        'OSCAR DE MONZARZ EIRL',
        'INVERSIONES DALSA SAC'
    ]);

    return [
        'name' => strtoupper(strtolower($name)),
        'address' => $faker->address,
        'hours' => $faker->numberBetween($min = 100, $max = 300),
        'ruc' => $faker->e164PhoneNumber,
        'contact_name' => $faker->userName,
        'contact_telephone' => $faker->phoneNumber,
        'contact_email' => $faker->companyEmail,
    ];
});
