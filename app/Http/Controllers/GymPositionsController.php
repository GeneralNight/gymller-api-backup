<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymPositions;
use Illuminate\Http\Request;

class GymPositionsController extends Controller
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
            "data"=> GymPositions::where("gym_id",intVal($gymId))->get()
        ]);
    }

    public function store(Request $request, $gymId) {
        $data = $request->all();
        $gymExist = Gym::where("id",intVal($gymId))->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $positionExist = GymPositions::where([
            "gym_id" => intVal($gymId),
            "name" => $data["name"]
        ])->count();

        if($positionExist > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's a position with this name already"
            ]);
        }

        $data["gym_id"] = $gymId;

        return response()->json([
            "msg" => "success",
            "data" => GymPositions::create($data)
        ]);
    }

    public function update(Request $request, $gymId, $positionId) {
        $data = $request->all();
        $gymExist = Gym::where("id",intVal($gymId))->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $positionExist = GymPositions::where([
            "gym_id" => intVal($gymId),
            "id" => intVal($positionId)
        ])->count();

        if($positionExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any position with this params"
            ]);
        }

        $positionExistWithEditedName = GymPositions::where([
            "gym_id" => intVal($gymId),
            "name" => $data["name"]
        ])->count();

        if($positionExistWithEditedName > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's a position with this name already"
            ]);
        }

        $positionToUpdate = GymPositions::where([
            "id" => intVal($positionId),
            "gym_id" => intVal($gymId)
        ])->first();

        $positionToUpdate->name = $data["name"];


        return response()->json([
            "msg" => "success",
            "data" => $positionToUpdate->save()
        ]);
    }

    public function delete($gymId,$positionId) {
        $gymExist = Gym::where([
            "id" => intVal($gymId),
        ])->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $positionExist = GymPositions::where([
            "id" => intVal($positionId),
        ])->count() || 0;

        if($positionExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any position with this params"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => GymPositions::find($positionId)->delete()
        ]);
    }
    //
}
