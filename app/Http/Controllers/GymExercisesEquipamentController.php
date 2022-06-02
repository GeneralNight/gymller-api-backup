<?php

namespace App\Http\Controllers;

use App\Models\ClientGymTrainingSheetExercise;
use App\Models\Gym;
use App\Models\GymExercises;
use App\Models\GymExercisesEquipament;
use Illuminate\Http\Request;


class GymExercisesEquipamentController extends Controller
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

    public function all($slug,$exerciseId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymExercisesEquipament::where([
//                "gym_id"=>intVal($gymExist->id),
                "exercise_id"=>intVal($exerciseId)
            ])->join("gym_equipaments","gym_exercises_equipaments.equipament_id","gym_equipaments.id")->get(),
            "exercise"=> GymExercises::where([
                "gym_id"=>intVal($gymExist->id),
                "id"=>intVal($exerciseId)
            ])->get()
        ]);
    }

    public function index($slug, $exerciseId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $exerciseExist = GymExercises::where([
            'gym_id' => intVal($gymExist->id),
            'id' => intval($exerciseId)
        ])->first();

        if(!$exerciseExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any exercise with this id in this gym",
                "code"=> "002"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> $exerciseExist
        ]);

    }

    public function store($slug, $exerciseId, Request $request) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $exerciseExist = GymExercises::where([
            'gym_id' => intval($gymExist->id),
            'id' => intval($exerciseId)
        ])->first();

        if(!$exerciseExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any exercise with this id",
                "code"=> "002"
            ]);
        }

        $arr = array_filter($data["fields"], function($value) { return !is_null($value) && $value !== ''; });
        $ammountCreated = 0;
        for ($i=0;$i<count($arr);$i++) {
            if(GymExercisesEquipament::create([
                "gym_id" => $gymExist->id,
                "exercise_id" => $exerciseId,
                "equipament_id" => $arr[$i]
            ])) {
                $ammountCreated++;
            }
        }

        if(count($arr)==$ammountCreated) {
            return response()->json([
                'msg' => 'success',
                'data' => $arr
            ]);
        }
    }


    public function delete($slug,$exerciseId,$equipId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $exerciseExist = GymExercisesEquipament::where([
            'gym_id' => intval($gymExist->id),
            'equipament_id' => intval($equipId),
            'exercise_id' => intval($exerciseId)
        ])->first();

        if(!$exerciseExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any connection  with this id",
                "code"=> "002"
            ]);
        }

        $EquipamentsOfExercise = ClientGymTrainingSheetExercise::where("id",$exerciseExist->id)->first();

        if($EquipamentsOfExercise) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's training sheets that depends this exercise.",
                "code"=> "003"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymExercisesEquipament::find($exerciseExist->id)->delete(),
        ]);


    }

    //
}
