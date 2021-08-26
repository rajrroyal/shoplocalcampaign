<?php

// @TODO StoreFactory

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Store;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [
        'user_id' => function() { return factory(App\Models\User::class)->create()->id; },
    ];
});

$factory->state(Store::class, 'shopify', function(Faker $faker) {
    return [
        'source' => 'shopify',
    ];
});

$factory->state(Store::class, 'ecwid', function(Faker $faker) {
    return [

    ];
});

$factory->state(Store::class, 'complete', function(Faker $faker) {
    return [

    ];
});


// factory.stub
