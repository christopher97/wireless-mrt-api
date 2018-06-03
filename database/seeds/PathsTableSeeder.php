<?php

use Illuminate\Database\Seeder;
use App\Models\Path;

class PathsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      for($i=1; $i<8; $i++) {
        Path::create(['lane_id' => 1, 'station1_id' => $i, 'station2_id' => $i+1, 'time' => mt_rand(2,3)]);
      }
    }
}
