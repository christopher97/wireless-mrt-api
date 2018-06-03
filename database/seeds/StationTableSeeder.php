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
        Station::create(['name' => 'blok m', 'lat' => -6.243332, 'long' => 106.8019176]);
        Station::create(['name' => 'senayan', 'lat' => -6.2278812, 'long' => 106.8009891]);
        Station::create(['name' => 'benhil', 'lat' => -6.2170993, 'long' => 106.8152906]);
        Station::create(['name' => 'tosari', 'lat' => -6.198410399, 'long' => 106.8231216]);
        Station::create(['name' => 'sarinah', 'lat' => -6.1882163, 'long' => 106.8227647]);
        Station::create(['name' => 'monas', 'lat' => -6.17625700, 'long' => 106.822868]);
        Station::create(['name' => 'mangga besar', 'lat' => -6.152269800, 'long' => 106.8174606]);
        Station::create(['name' => 'kota', 'lat' => -6.13779730, 'long' => 106.8140107]);
    }
}
