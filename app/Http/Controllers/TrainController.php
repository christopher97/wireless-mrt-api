<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;
use App\Models\User;
use App\Models\Path;
use App\Models\Station;
use JWTAuth;
use Validator, Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

class TrainController extends Controller
{
    protected $train, $path, $station;

    public function __construct(Train $train, Path $path, Station $station) {
        $this->train = $train;
        $this->path = $path;
        $this->station = $station;
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

        return response()->json(['error' => 'Train Occupied'], 400);
    }

    public function triggerMoving(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $train = $user->train;

        $train->moving = ($train->moving + 1) % 2;


        // if train arrived at endpoint, change the direction
        $start= $this->getStart();
        $end = $this->getEnd();

        // if train toggled to stop, flip the moving int
        if ($train->moving == 0) {
            $pt = $this->getPath();

            for ($i = 0; $i < count($pt); $i++) {
                if ($train->last_id == $pt[$i]->station1_id && $train->direction == 0) {
                    $train->last_id = $pt[$i]->station2_id;
                    break;
                }
                if ($train->last_id == $pt[$i]->station2_id && $train->direction == 1) {
                    $train->last_id = $pt[$i]->station1_id;
                    break;
                }
            }
        }

        if ($train->last_id == $start)
            $train->direction = 0;
        else if ($train->last_id == $end)
            $train->direction = 1;

        $train->save();
        return response()->json(['message' => 'success'], 200);
    }

    // this is hardcoded for now
    public function getStart() {
        return 1;
    }

    // this is hardcoded for now
    public function getEnd() {
        $stations = $this->station->all();
        $end = $stations[count($stations)-1]->id;

        return $end;
    }

    public function getPath() {
        $pt = $this->path->all();
        return $pt;
    }

    private function findNextStation($currStation, $direction) {
        $pt = $this->getPath();

        if ($direction == 0) {
            for ($i = 0; $i < count($pt); $i++) {
                $curr = $pt[$i];

                if ($curr->station1_id == $currStation) {
                    return $curr->station2;
                }
            }
        } else {
            for ($i = 0; $i < count($pt); $i++) {
                $curr = $pt[$i];

                if ($curr->station2_id == $currStation) {
                    return $curr->station1;
                }
            }
        }
    }

    /**
     *  ETA Algorithm
     *
     *  get list of paths
     *  get list of all active trains
     *
     *  iterate over the trains
     *
     *  if current train's position has passed the user's station, then skip
     *  else, calculate the ETA
     */

    public function calculateETA($stationID) {
        // implement here
        $pt = $this->path->all();
        $trains = $this->train->whereNotNull("driver_id")->get();

        $result = [];
        for ($i = 0; $i < count($trains); $i++) {
            $currTrain = $trains[$i];

            $eta = 0;
            $passed = true;
            $currStation = $currTrain->last_id;
            // if train is moving from station1_id to station2_id
            if ($currTrain->direction == 0) {

                // if train's last pos is current station
                if ($currStation == $stationID) {
                    // if train has left the current station
                    if ($currTrain->moving == 1)
                        continue;
                    $passed = false;
                } else {
                    while (true) {
                        $found = false;
                        for ($j = 0; $j < count($pt); $j++) {
                            $temp = $pt[$j];

                            if ($temp->station1_id == $currStation) {
                                $found = true;
                                $eta += $temp->time;

                                if ($temp->station2_id == $stationID)
                                    $passed = false;

                                $currStation = $temp->station2_id;
                                break;
                            }
                        }
                        if (!$found)
                            break;
                        if (!$passed)
                            break;
                    }
                }

                $eta *= 60;
                if ($currTrain->moving == 1) {
                    $time = Carbon::now();
                    $diff = $time->diffInSeconds($currTrain->updated_at);
                    $eta -= $diff;
                }
            }
            // if train is moving from station2_id to station1_id
            else {

                // if train's last pos is current station
                if ($currStation == $stationID) {
                    // if train has left the current station
                    if ($currTrain->moving == 1)
                        continue;
                    $passed = false;
                } else {
                    while (true) {
                        $found = false;
                        for ($j = 0; $j < count($pt); $j++) {
                            $temp = $pt[$j];

                            if ($temp->station2_id == $currStation) {
                                $found = true;
                                $eta += $temp->time;

                                if ($temp->station1_id == $stationID)
                                    $passed = false;

                                $currStation = $temp->station1_id;
                                break;
                            }
                        }
                        if (!$found)
                            break;
                        if (!$passed)
                            break;
                    }
                }

                $eta *= 60;
                if ($currTrain->moving == 1) {
                    $time = Carbon::now();
                    $diff = $time->diffInSeconds($currTrain->updated_at);
                    $eta -= $diff;
                }
            }

            $prev = $currTrain->last->name;
            $next = $this->findNextStation($currTrain->last_id, $currTrain->direction)->name;

            $currTrain->prev = $prev;
            $currTrain->next = $next;

            if (!$passed) {
                $currTrain->eta = $eta;
                array_push($result, $currTrain);
            }
        }

        if ($result) {
            return response()->json(['trains' => $result], 200);
        }
        return response()->json(['error' => 'No train moving to your direction currently, please check again in a minute.'], 400);
    }

//    $time = Carbon::now();
//    $diff = $time->diffInSeconds($train->updated_at);
//    $eta = ($pt->time * 60) - $diff;
//
//    if ($train->direction == 0) {
//        // if direction is forward
//        $pt = $this->path->where('station1_id', '=', $train->last_id)->first();
//    } else {
//        // if direction is backwards
//        $pt = $this->path->where('station2_id', '=', $train->last_id)->first();
//    }
}
