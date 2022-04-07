<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Repositories\Counters\Counter::class, function (Faker $faker) {
    $name = $faker->unique()->randomElement([
        'SUZELIN OROS',
        'YESSI SEDANO',
        'ARTURO CORNEJO',
        'ANDRES ORTIZ',
        'JOSE MANRIQUE',
        'GIANCARLO ATUNCAR',
        'AUGUSTO MORON',
        'CARMEN VEGA',
        'ISAAC RODRIGUEZ',
        'GIOVANNA CRUZALEGUI'
    ]);

    $explodeName = explode(" ",$name);

    return [
        'name'              => $explodeName[0],
        'last_name'         => $explodeName[1],
        'email'             => strtolower($explodeName[0])."@gmail.com",
        'email_verified_at' => now(),
        'state'             => 1,
        'last_login'        => now(),
        'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'password_plain'    => 'password',
        'remember_token'    => Str::random(10),
        'dni'               => $faker->randomNumber(8)
    ];
});
