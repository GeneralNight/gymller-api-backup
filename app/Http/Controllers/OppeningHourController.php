<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymOppeningHour;
use App\Models\OppeningHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OppeningHourController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function all($gymId) {
        $gymExist = Gym::where("id",intVal($gymId))->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymOppeningHour::orderBy("week_day","asc")->get()
        ]);
    }

    public function store(Request $request) {
        $data = $request->all();
        $gymExist = Gym::where("id",intVal($data["gym_id"]))->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $alreadyHour = GymOppeningHour::where([
            ["week_day",intVal($data["week_day"])],
            ["gym_id",intVal($data["gym_id"])]
        ])->count() || 0;

        if($alreadyHour > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A oppening hour already exist on this week day"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymOppeningHour::create($data)
        ]);
    }

    public function update(Request $request, $weekDay) {
        $data = $request->all();
        $gymExist = Gym::where("id",intVal($data["gym_id"]))->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $currentHour = GymOppeningHour::where([
            ["week_day",intVal($weekDay)],
            ["gym_id",intVal($data["gym_id"])]
        ])->first();

        $currentHour->openning_hour = $data["openning_hour"];
        $currentHour->closing_hour = $data["closing_hour"];


        return response()->json([
            "msg" => "success",
            "data" => $currentHour->save()
        ]);
    }

    public function delete($gymId,$hourId) {
        $gymExist = Gym::where("id",intVal($gymId))->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $hourExist = GymOppeningHour::where([
            ["id",intVal($hourId)],
            ["gym_id",intVal($gymId)]
        ])->count();

        if($hourExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any hour with this params"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => GymOppeningHour::find($hourId)->delete()
        ]);
    }

    //
}
