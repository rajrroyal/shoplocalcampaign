<?php

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DemoUserSeeder::class);
        $this->call(DemoDavesNewWorldSeeder::class);
    }
}

// seeder.stub
