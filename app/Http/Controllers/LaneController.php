<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lane;

class LaneController extends Controller
{
    protected $lane;

    public function __construct(Lane $lane) {
        $this->lane = $lane;
    }

    public function fetch() {
        $lanes = $this->lane->all();

        return response()->json(['lanes' => $lanes], 200);
    }

    public function find($id) {
        $lane = $this->lane->find($id);

        return response()->json(['lane' => $lane], 200);
    }
}
