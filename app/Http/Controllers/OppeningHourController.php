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

    public function all($slug) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code" => "001"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymOppeningHour::where("gym_id",$gymExist->id)->orderBy("week_day","asc")->get()
        ]);
    }

    public function index($slug,$weekDay) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $oppeningExist = GymOppeningHour::where([
            "gym_id" => $gymExist->id,
            "week_day" => $weekDay
        ])->first();

        if(!$oppeningExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any oppening hour with this id",
                "code"=> "002"
            ]);
        }


        return response()->json([
            "msg"=> "success",
            "data" => $oppeningExist
        ]);

    }

    public function store(Request $request, $slug) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code" => '001'
            ]);
        }

        $alreadyHour = GymOppeningHour::where([
            ["week_day",intVal($data["week_day"])],
            ["gym_id",intVal($gymExist->id)]
        ])->count() || 0;

        if($alreadyHour > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A oppening hour already exist on this week day",
                "code" => '002'
            ]);
        }

        $data["gym_id"] = intVal($gymExist->id);

        return response()->json([
            "msg"=> "success",
            "data"=> GymOppeningHour::create($data)
        ]);
    }

    public function update(Request $request, $slug, $weekDay) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code" => "001"
            ]);
        }


        $currentHour = GymOppeningHour::where([
            ["week_day",intVal($weekDay)],
            ["gym_id",intVal($gymExist->id)]
        ])->first();

        if(!$currentHour) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any oppening hour in this week day yet",
                "code" => "002"
            ]);
        }

        $currentHour->openning_hour = $data["openning_hour"];
        $currentHour->closing_hour = $data["closing_hour"];


        return response()->json([
            "msg" => "success",
            "data" => $currentHour->save()
        ]);
    }

    public function delete($slug,$weekDay) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code" => '001'
            ]);
        }

        $hourExist = GymOppeningHour::where([
            ["week_day",intVal($weekDay)],
            ["gym_id",intVal($gymExist->id)]
        ])->first();

        if(!$hourExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any hour in this week day",
                "code" => '002'
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => GymOppeningHour::find($hourExist->id)->delete()
        ]);
    }

    //
}
