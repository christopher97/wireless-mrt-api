<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Train;

class TrainController extends Controller
{
    protected $train;

    public function __construct(Train $train) {
        $this->train = $train;
    }

    public function fetch() {
        $trains = $this->train->all();

        return response()->json(['trains' => $trains], 200);
    }

    public function find($id) {
        $train = $this->train->find($id);

        return response()->json(['train' => $train], 200);
    }
}
