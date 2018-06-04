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

    public function active() {
        // get list of active trains
        $trains = $this->train->whereNotNull('driver_id')->with('lane')->with('last')->get();

        // get next station
        for ($i = 0; $i < count($trains); $i++) {
            $currTrain = $trains[$i];
            if ($currTrain->direction == 0) {
                $pt = $this->path->where('station1_id', '=', $currTrain->last_id)->first();
            } else {
                $pt = $this->path->where('station2_id', '=', $currTrain->last_id)->first();
            }
            $trains[$i]->next = $pt;
        }

        if ($trains)
            return response()->json(['trains' => $trains], 200);
        return response()->json(['message' => 'not found'], 404);
    }

    public function inactive() {
        // get list of inactive trains
        $trains = $this->train->whereNull('driver_id')->with('lane')->with('last')->get();

        if ($trains)
            return response()->json(['trains' => $trains], 200);
        return response()->json(['message' => 'not found'], 404);
    }

    public function chooseTrain(Request $request) {
        // put user id to driver id
        $train = $this->train->find($request->id);
        $user = JWTAuth::parseToken()->authenticate();

        if ($train->driver_id == null) {
            $train->driver_id = $user->id;
            $train->save();
            return response()->json(['message' => 'Success'], 200);
        }

        return response()->json(['message' => 'Train Occupied'], 400);
    }

    public function triggerMoving(Request $request) {
        // implement here
    }

    public function calculateETA($trainID, $stationID) {
        // implement here
        $pt = $this->path->all();
        $train = $this->train->find($trainID);

        // if ($train->direction == 0) {
        //     // if direction is forward
        //     $pt = $this->path->where('station1_id', '=', $train->last_id)->first();
        // } else {
        //     // if direction is backwards
        //     $pt = $this->path->where('station2_id', '=', $train->last_id)->first();
        // }

        $eta = 0;
        $max = count($pat);
        // if train is idle
        if ($train->moving == 0) {
            if ($train->direction == 0) {
                // if train has passed current station
                // if train has not passed
            } else {
                // if train has passed current station
                // if train has not passed
            }
        }
        // if train is moving
        else {
            if ($train->direction == 0) {
                // if train has passed current station
                // if train has not passed
            } else {
                // if train has passed current station
                // if train has not passed
            }
            $time = Carbon::now();
            $diff = $time->diffInSeconds($train->updated_at);
            $eta = ($pt->time * 60) - $diff;
        }

        dd($eta);
    }

    public function getPath() {
        $pt = $this->path->all();
        return $pt;
    }
}
