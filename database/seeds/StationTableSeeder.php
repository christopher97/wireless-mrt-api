<?php

use Illuminate\Database\Seeder;
use App\Models\Station;

class StationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Station::create(['name' => 'blok m', 'lat' => -6.243293, 'long' => 106.801971]);
        Station::create(['name' => 'senayan', 'lat' => -6.227727, 'long' => 106.801032]);
        Station::create(['name' => 'benhil', 'lat' => -6.217103, 'long' => 106.815286]);
        Station::create(['name' => 'tosari', 'lat' => -6.198419, 'long' => 106.823103]);
        Station::create(['name' => 'sarinah', 'lat' => -6.188206, 'long' => 106.822765]);
        Station::create(['name' => 'monas', 'lat' => -6.176261, 'long' => 106.822839]);
        Station::create(['name' => 'mangga besar', 'lat' => -6.152241, 'long' => 106.817455]);
        Station::create(['name' => 'kota', 'lat' => -6.137792, 'long' => 106.814009]);
    }
}
