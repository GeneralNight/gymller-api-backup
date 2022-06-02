<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymEquipaments;
use App\Models\GymExercises;
use App\Models\GymExercisesEquipament;
use Illuminate\Http\Request;


class GymEquipamentsController extends Controller
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
                "code"=> "001"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymEquipaments::where("gym_id",intVal($gymExist->id))->get()
        ]);
    }

    public function index($slug, $equipId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $equipExist = GymEquipaments::where([
            'gym_id' => intVal($gymExist->id),
            'id' => intval($equipId)
        ])->first();

        if(!$equipExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any equipament with this id in this gym",
                "code"=> "002"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> $equipExist
        ]);

    }

    public function store($slug, Request $request) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $alreadyEquipName = GymEquipaments::where([
            'gym_id' => intval($gymExist->id),
            'name' => $data['name'],
        ])->first();

        if($alreadyEquipName) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's other equipament with this name already",
                "code"=> "002"
            ]);
        }

        $alreadyEquipNumber = GymEquipaments::where([
            'gym_id' => intval($gymExist->id),
            'number' => intval($data['number']),
        ])->first();

        if($alreadyEquipNumber) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's other equipament with this number already",
                "code"=> "003"
            ]);
        }

        $data['gym_id'] = $gymExist->id;
        $data['status'] = true;

        return response()->json([
            'msg' => 'success',
            'data' => GymEquipaments::create($data)
        ]);
    }

    public function update($slug, Request $request, $equipId) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $equipExist = GymEquipaments::where([
            'gym_id' => intval($gymExist->id),
            'id' => intval($equipId)
        ])->first();

        if(!$equipExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any equipament with this id",
                "code"=> "002"
            ]);
        }

        if($equipExist->name != $data['name']) {
            $alreadyEquipName = GymEquipaments::where([
                'gym_id' => intval($gymExist->id),
                'name' => $data['name'],
            ])->first();

            if($alreadyEquipName) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other equipament with this name already",
                    "code"=> "003"
                ]);
            }
        }
        if($equipExist->number != $data['number']) {
            $alreadyEquipNumber = GymEquipaments::where([
                'gym_id' => intval($gymExist->id),
                'number' => intval($data['number']),
            ])->first();

            if($alreadyEquipNumber) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other equipament with this number already",
                    "code"=> "004"
                ]);
            }
        }

        $equipExist->name = $data['name'];
        $equipExist->number = $data['number'];
        $equipExist->status = $data['status'];

        return response()->json([
            'msg' => 'success',
            'data' => $equipExist->save()
        ]);
    }

    public function delete($slug,$equipId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $equipamentExist = GymEquipaments::where([
            'gym_id' => intval($gymExist->id),
            'id' => intval($equipId)
        ])->first();

        if(!$equipamentExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any equipament with this id",
                "code"=> "002"
            ]);
        }

        $ExercisesOfEquipament = GymExercisesEquipament::where("equipament_id",$equipamentExist->id)->first();

        if($ExercisesOfEquipament) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's exercises that depends this equipament.",
                "code"=> "003"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymEquipaments::find($equipamentExist->id)->delete(),
        ]);


    }

    public function exercisesNotRelated($slug,$exerciseId) {
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

//        $crashedCarIds = GymExercisesEquipament::all();
        $equips = GymEquipaments::get();
        $newArr = [];
        for($i=0;$i<count($equips);$i++) {
            if(!GymExercisesEquipament::where([
                "equipament_id"=>$equips[$i]["id"],
                "exercise_id"=> $exerciseId
            ])->first()) {
                $newArr[] = GymEquipaments::where([
                    "id" => $equips[$i]["id"]
                ])->first();
            }
        }

        return response()->json([
           "msg" => "success",
           "data" => $newArr
        ]);
    }


    //
}
