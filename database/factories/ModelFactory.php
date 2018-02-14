<?php

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'email'=> $faker->email,
        'nickname' => $faker->name,
        'password' => bcrypt(env('COMMON_PASSWORD')),
        'status' => 'active',
        'grade' => 'general',
        'birthday' => Carbon\Carbon::parse()
            ->between(
                Carbon\Carbon::now()->subYears(30),
                Carbon\Carbon::now()->subYears(20)),
        'gender' => mt_rand(0,1) == 0 ? 'male' : 'female',
        'image_id' => factory(App\Models\Image::class)->create()->id,
        'newsletters_accepted' => mt_rand(0,1),
        'terms_of_service_accepted' => true,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
    ];
});