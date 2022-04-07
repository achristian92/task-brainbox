<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Repositories\Supervisors\Supervisor::class, function (Faker $faker) {
    return [
        'name'              => $faker->name,
        'last_name'         => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'state'             => 1,
        'last_login'        => now(),
        'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'password_plain'    => 'password',
        'remember_token'    => Str::random(10),
        'dni'               => $faker->randomNumber(8)
    ];
});
