<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Forum;
use Faker\Generator as Faker;

$factory->define(Forum::class, function (Faker $faker) {
    return [
      'title' => $faker->text,
      'content' => $faker->sentence,
      'likes' => 0,
      'comments' => 0
    ];
});
