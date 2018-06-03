<?php

use Illuminate\Database\Seeder;
use App\Models\Train;

class TrainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Train::create(['lane_id' => 1, 'last_id' => 1, 'driver_id' => NULL, 'direction' => 0]);
        Train::create(['lane_id' => 1, 'last_id' => 8, 'driver_id' => NULL, 'direction' => 1]);
        Train::create(['lane_id' => 1, 'last_id' => 3, 'driver_id' => NULL, 'direction' => 0]);
    }
}
