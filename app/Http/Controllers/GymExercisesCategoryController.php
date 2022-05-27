<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymExercisesCategory;
use Illuminate\Http\Request;


class GymExercisesCategoryController extends Controller
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
            "data"=> GymExercisesCategory::where("gym_id",intVal($gymExist->id))->get()
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

        $alreadyExerciseCatDesc = GymExercisesCategory::where([
            'gym_id' => intval($gymExist->id),
            'description' => $data['description'],
        ])->first();

        if($alreadyExerciseCatDesc) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's other exercise category with this description already",
                "code"=> "002"
            ]);
        }

        $data['gym_id'] = $gymExist->id;

        return response()->json([
            'msg' => 'success',
            'data' => GymExercisesCategory::create($data)
        ]);
    }

    public function update($slug, Request $request, $exerciseCatId) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $exerciseCatExist = GymExercisesCategory::where([
            'gym_id' => intval($gymExist->id),
            'id' => intval($exerciseCatId)
        ])->first();

        if(!$exerciseCatExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any exercise category with this id",
                "code"=> "002"
            ]);
        }

        if($exerciseCatExist->description != $data['description']) {
            $alreadyExerciseCatDesc = GymExercisesCategory::where([
                'gym_id' => intval($gymExist->id),
                'description' => $data['description'],
            ])->first();

            if($alreadyExerciseCatDesc) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other exercise category with this description already",
                    "code"=> "003"
                ]);
            }
        }

        $exerciseCatExist->description = $data['description'];

        return response()->json([
            'msg' => 'success',
            'data' => $exerciseCatExist->save()
        ]);
    }

    public function delete($slug,$exerciseCatId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $exerciseCatExist = GymExercisesCategory::where([
            'gym_id' => intval($gymExist->id),
            'id' => intval($exerciseCatId)
        ])->first();

        if(!$exerciseCatExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any exercise category with this id",
                "code"=> "002"
            ]);
        }

        return response()->json([
            "msg"=> "error",
            "data"=> "There's equipaments that depends this exercise.",
            "code"=> "003"
        ]);


    }

    //
}
