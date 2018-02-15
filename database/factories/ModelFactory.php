<?php

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'email'=> $faker->email,
        'password' => bcrypt(env('COMMON_PASSWORD')),
        'status' => 'inactive',
        'email_accepted' => mt_rand(0,1) ? true : false,
        'terms_of_service_accepted' => true,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
    ];
});