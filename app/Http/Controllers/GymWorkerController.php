<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymWorker;
use Illuminate\Http\Request;

class GymWorkerController extends Controller
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
                "data"=> GymWorker::where("gym_id",intVal($gymId))->get()
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

        $alreadyWorker = GymWorker::where([
                ["cpf",$data["cpf"]],
                ["gym_id",intVal($gymId)]
            ])->count() || 0;

        if($alreadyWorker > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A already works exists in this CPF"
            ]);
        }

        $data["gym_id"] = intVal($gymId);

        return response()->json([
            "msg"=> "success",
            "data"=> GymWorker::create($data)
        ]);
    }

    //
}
