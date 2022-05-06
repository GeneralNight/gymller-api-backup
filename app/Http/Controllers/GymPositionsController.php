<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymPositions;
use App\Models\GymWorker;
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

    public function all($slug) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> GymPositions::where("gym_id",intVal($gymExist->id))->get()
        ]);
    }

    public function index($slug,$positionId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $positionExist = GymPositions::where([
            "gym_id" => $gymExist->id,
            "id" => $positionId
        ])->first();

        if(!$positionExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any position with this id",
                "code"=> "002"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data" => $positionExist
        ]);

    }

    public function store(Request $request, $slug) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code" => "001"
            ]);
        }

        $positionExist = GymPositions::where([
            "gym_id" => intVal($gymExist->id),
            "name" => $data["name"]
        ])->count();

        if($positionExist > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's a position with this name already",
                "code" => "002"
            ]);
        }

        $data["gym_id"] = $gymExist->id;

        return response()->json([
            "msg" => "success",
            "data" => GymPositions::create($data)
        ]);
    }

    public function update(Request $request, $slug, $positionId) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code" => '001'
            ]);
        }

        $positionExist = GymPositions::where([
            "gym_id" => intVal($gymExist->id),
            "id" => intVal($positionId)
        ])->count();

        if($positionExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any position with this params",
                "code" => '002'
            ]);
        }

        $positionExistWithEditedName = GymPositions::where([
            "gym_id" => intVal($gymExist->id),
            "name" => $data["name"]
        ])->count();

        if($positionExistWithEditedName > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's a position with this name already",
                "code" => '003'
            ]);
        }

        $positionToUpdate = GymPositions::where([
            "id" => intVal($positionId),
            "gym_id" => intVal($gymExist->id)
        ])->first();

        $positionToUpdate->name = $data["name"];


        return response()->json([
            "msg" => "success",
            "data" => $positionToUpdate->save()
        ]);
    }

    public function delete($slug,$positionId) {
        $gymExist = Gym::where([
            "slug" => $slug,
        ])->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code" => "001"
            ]);
        }

        $positionExist = GymPositions::where([
            "id" => intVal($positionId),
            "gym_id" => intVal($gymExist->id)
        ])->count() || 0;

        if($positionExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any position with this params",
                "code" => '002'
            ]);
        }

        $workersInThisPosition = GymWorker::where([
            "gym_id" => intVal($gymExist->id),
            "position_id" => intVal($positionId),
        ])->first();

        if($workersInThisPosition) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's some worker in this position",
                "code" => '003'
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => GymPositions::find($positionId)->delete()
        ]);
    }
    //
}
