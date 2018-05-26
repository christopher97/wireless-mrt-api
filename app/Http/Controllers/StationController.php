<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;

class StationController extends Controller
{
    protected $station;

    public function __construct(Station $station) {
        $this->station = $station;
    }

    public function fetch() {
        $stations = $this->station->all();

        return response()->json(['stations' => $stations], 200);
    }

    public function find($id) {
        $station = $this->station->find($id);

        if ($station) {
            return response()->json(['station' => $station], 200);
        }

        return response()->json(['station' => $station, 'message' => 'invalid station id'], 400);
    }

    public function nearby(Request $request) {
        $lat = floatval($request->lat);
        $long = floatval($request->long);

        $stations = $this->station->all();      // get all stations
        $total = count($stations);              // total stations

        $min = 999999999;                       // minimum distance
        $chosen = -1;                           // chosen index

        for($i=0; $i<$total; $i++) {

            $x = pow(($lat - $stations[$i]->lat),2);
            $y = pow(($long - $stations[$i]->long),2);

            $manhattan = sqrt($x + $y);
            echo $manhattan . "\xA";
            if ($manhattan < $min) {
                $min = $manhattan;
                $chosen = $i;
            }

        }

        $chosen = $stations[$chosen];

        return response()->json(['station' => $chosen], 200);
    }
}
