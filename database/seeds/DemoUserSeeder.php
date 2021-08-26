<?php

use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class)->create([
            'name' => 'Corey Piitz',
            'email' => 'cpiitz@shopcity.com',
            'password' => bcrypt('topsecret'),
            'admin' => 1
        ]);

        factory(App\Models\User::class)->create([
            'name' => 'Dave Keefe',
            'email' => 'dkeefe@shopcity.com',
            'password' => bcrypt('topsecret'),
            'admin' => 1
        ]);
    }
}
