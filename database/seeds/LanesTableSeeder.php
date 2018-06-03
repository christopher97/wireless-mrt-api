<?php

use Illuminate\Database\Seeder;
use App\Models\Lane;

class LanesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lane::create(['name' => "Blok M - Kota"]);
    }
}
