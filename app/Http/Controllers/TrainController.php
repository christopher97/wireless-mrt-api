<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;
use App\Models\User;
use App\Models\Path;
use JWTAuth;
use Validator, Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

class TrainController extends Controller
{
    protected $train, $path;

    public function __construct(Train $train, Path $path) {
        $this->train = $train;
        $this->path = $path;
    }

    public function fetch() {
        $trains = $this->train->all();

        return response()->json(['trains' => $trains], 200);
    }

    public function find($id) {
        $train = $this->train->find($id);

        return response()->json(['train' => $train], 200);
    }

    public function chooseTrain(Request $request) {
        // put user id to driver id
        $train = $this->train->find($request->id);
        $user = JWTAuth::parseToken()->authenticate();

        $train->driver_id = $user->id;
        $train->save();

        return response()->json(['train' => $train], 200);
    }

    public function calculateETA($id) {
        // implement here
        $pt;
        $train = $this->train->find($id);

        if ($train->direction == 0) {
            // if direction is 0
            $pt = $this->path->where('station1_id', '=', $train->last_id)->first();
        } else {
            // if direction is 1
            $pt = $this->path->where('station2_id', '=', $train->last_id)->first();
        }

        $time = Carbon::now();
        $diff = $time->diffInSeconds($train->updated_at);
        echo $train->updated_at;
        echo $time;
        die;
        dd($diff);

        $eta = ($pt->time * 60) - $diff;
        dd($eta);
    }
}
