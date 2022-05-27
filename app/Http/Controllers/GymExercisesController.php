<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymExercises;
use Illuminate\Http\Request;


class GymExercisesController extends Controller
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
            "data"=> GymExercises::where("gym_id",intVal($gymExist->id))->get()
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

        $alreadyExerciseName = GymExercises::where([
            'gym_id' => intval($gymExist->id),
            'name' => $data['name'],
        ])->first();

        if($alreadyExerciseName) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's other exercise with this name already",
                "code"=> "002"
            ]);
        }

        $data['gym_id'] = $gymExist->id;
        $data['status'] = true;

        return response()->json([
            'msg' => 'success',
            'data' => GymExercises::create($data)
        ]);
    }

    public function update($slug, $exerciseId, Request $request) {
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

        if($exerciseExist->name != $data['name']) {
            $alreadyExerciseName = GymExercises::where([
                'gym_id' => intval($gymExist->id),
                'name' => $data['name'],
            ])->first();

            if($alreadyExerciseName) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other exercise with this name already",
                    "code"=> "003"
                ]);
            }
        }

        $exerciseExist->name = $data['name'];
        $exerciseExist->exercise_category_id = $data['exercise_category_id'];
        $exerciseExist->description = $data['description'];
        $exerciseExist->status = $data['status'];

        return response()->json([
            'msg' => 'success',
            'data' => $exerciseExist->save()
        ]);
    }

    public function delete($slug,$exerciseId) {
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

        return response()->json([
            "msg"=> "error",
            "data"=> "There's equipaments that depends this exercise.",
            "code"=> "003"
        ]);


    }

    //
}
