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

        // Verify Gym exist
        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        // Verify worker already exist in cpf
        $alreadyWorkerCPF = GymWorker::where([
                ["cpf",$data["cpf"]],
                ["gym_id",intVal($gymId)]
        ])->count() || 0;

        if($alreadyWorkerCPF > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A already works exists in this CPF"
            ]);
        }

        // Verify worker already exist in rg
        $workerExistRG = GymWorker::where([
                "gym_id" => intVal($gymId),
                "rg" => $data["rg"],
        ])->count() || 0;

        if($workerExistRG > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's other worker in this rg"
            ]);
        }

        // Verify worker already exist in username
        $alreadyWorkerUsername = GymWorker::where([
                ["username",$data["username"]],
                ["gym_id",intVal($gymId)]
        ])->count() || 0;

        if($alreadyWorkerUsername > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "Other user alreaady has this username"
            ]);
        }

        $data["gym_id"] = intVal($gymId);

        return response()->json([
            "msg"=> "success",
            "data"=> GymWorker::create($data)
        ]);
    }

    public function update(Request $request, $gymId, $workerId) {
        $data = $request->all();
        $gymExist = Gym::where("id",intVal($gymId))->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $workerExist = GymWorker::where("id",intVal($workerId))->first();

        if(!$workerExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any worker with this id in this gym"
            ]);
        }



        if($workerExist["cpf"] != $data["cpf"]) {
            $workerExistCPF = GymWorker::where([
                "gym_id" => intVal($gymId),
                "cpf" => $data["cpf"],
            ])->count() || 0;

            if($workerExistCPF > 0) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other worker in this cpf"
                ]);
            }
        }

        if($workerExist["rg"] != $data["rg"]) {
            $workerExistRG = GymWorker::where([
                "gym_id" => intVal($gymId),
                "rg" => $data["rg"],
            ])->count() || 0;

            if($workerExistRG > 0) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other worker in this rg"
                ]);
            }
        }

        if($workerExist["username"] != $data["username"]) {
            $workerExistUsername = GymWorker::where([
                "gym_id" => intVal($gymId),
                "username" => $data["username"],
            ])->count() || 0;

            if($workerExistUsername > 0) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other worker in this username"
                ]);
            }
        }

        $worker = GymWorker::where([
            "id" => intVal($workerId),
            "gym_id" => intVal($gymId)
        ])->first();

        $worker->name = $data["name"];
        $worker->cpf = $data["cpf"];
        $worker->rg = $data["rg"];
        $worker->cep = $data["cep"];
        $worker->address = $data["address"];
        $worker->neighborhood = $data["neighborhood"];
        $worker->city = $data["city"];
        $worker->state = $data["state"];
        $worker->number = $data["number"];
        $worker->email = $data["email"];
        $worker->salary = $data["salary"];
        $worker->phone = $data["phone"];
        $worker->position_id = $data["position_id"];
        $worker->username = $data["username"];
        $worker->password = $data["password"];

        return response()->json([
            "msg" => "success",
            "data" => $worker->save()
        ]);


    }

    public function delete($gymId, $workerId) {
        $gymExist = Gym::where("id",$gymId)->count() || 0;

        if(!$gymExist) {
            return response()->json([
                "msg" => "error",
                "data" => "There's no gym with this id"
            ]);
        }

        $workerExist = GymWorker::where([
            "gym_id" => $gymId,
            "id" => $workerId
        ])->count() || 0;

        if(!$workerExist) {
            return response()->json([
                "msg" => "error",
                "data" => "There's any worker with this id in this gym"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => GymWorker::find($workerId)->delete()
        ]);
    }

    //
}
