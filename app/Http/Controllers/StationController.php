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

        return response()->json(['station' => $station], 200);
    }
}
