<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymWorker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function me()
    {
        return response()->json(
            auth()->gymworks()
        );
    }


    public function all($slug) {
        $gymExist = Gym::where("slug",$slug)->count() || 0;

        if($gymExist == 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id"
            ]);
        }

        $gymCurrent = Gym::where("slug",$slug)->first();

        return response()->json([
                "msg"=> "success",
                "data"=> GymWorker::where("gym_id",$gymCurrent["id"])->get()
        ]);

    }

    public function index($slug,$workerId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $workerExist = GymWorker::where([
            "gym_id" => $gymExist->id,
            "id" => $workerId
        ])->first();

        if(!$workerExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any worker with this id",
                "code"=> "002"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data" => $workerExist
        ]);

    }

    public function store(Request $request, $slug) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        // Verify Gym exist
        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=>"001"
            ]);
        }

        // Verify worker already exist in cpf
        $alreadyWorkerCPF = GymWorker::where([
                ["cpf",$data["cpf"]],
                ["gym_id",intVal($gymExist->id)]
        ])->count() || 0;

        if($alreadyWorkerCPF > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A already works exists in this CPF",
                "code"=>"002"
            ]);
        }

        // Verify worker already exist in rg
        $workerExistRG = GymWorker::where([
                "gym_id" => intVal($gymExist->id),
                "rg" => $data["rg"],
        ])->count() || 0;

        if($workerExistRG > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's other worker in this rg",
                "code"=>"003"
            ]);
        }

        // Verify worker already exist in username
        $alreadyWorkerUsername = GymWorker::where([
                ["username",$data["username"]],
                ["gym_id",intVal($gymExist->id)]
        ])->count() || 0;

        if($alreadyWorkerUsername > 0) {
            return response()->json([
                "msg"=> "error",
                "data"=> "Other user alreaady has this username",
                "code"=>"004"
            ]);
        }

        $data["gym_id"] = intVal($gymExist->id);
        $data["password"] = Hash::make($data["password"]);

        return response()->json([
            "msg"=> "success",
            "data"=> GymWorker::create($data)
        ]);
    }

    public function update(Request $request, $slug, $workerId) {
        $data = $request->all();
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=>"001"
            ]);
        }

        $workerExist = GymWorker::where("id",intVal($workerId))->first();

        if(!$workerExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any worker with this id in this gym",
                "code"=>"002"
            ]);
        }

        if($workerExist["cpf"] != $data["cpf"]) {
            $workerExistCPF = GymWorker::where([
                "gym_id" => intVal($gymExist->id),
                "cpf" => $data["cpf"],
            ])->count() || 0;

            if($workerExistCPF > 0) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other worker in this cpf",
                    "code"=>"003"
                ]);
            }
        }

        if($workerExist["rg"] != $data["rg"]) {
            $workerExistRG = GymWorker::where([
                "gym_id" => intVal($gymExist->id),
                "rg" => $data["rg"],
            ])->count() || 0;

            if($workerExistRG > 0) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other worker in this rg",
                    "code"=>"004"
                ]);
            }
        }

        if($workerExist["username"] != $data["username"]) {
            $workerExistUsername = GymWorker::where([
                "gym_id" => intVal($gymExist->id),
                "username" => $data["username"],
            ])->count() || 0;

            if($workerExistUsername > 0) {
                return response()->json([
                    "msg"=> "error",
                    "data"=> "There's other worker in this username",
                    "code"=>"005"
                ]);
            }
        }

        $worker = GymWorker::where([
            "id" => intVal($workerId),
            "gym_id" => intVal($gymExist->id)
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

    public function delete($slug, $workerId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg" => "error",
                "data" => "There's no gym with this id",
                "code" => "001"
            ]);
        }

        $workerExist = GymWorker::where([
            "gym_id" => $gymExist->id,
            "id" => $workerId
        ])->first();

        if(!$workerExist) {
            return response()->json([
                "msg" => "error",
                "data" => "There's any worker with this id in this gym",
                "code"=> "002"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => GymWorker::find($workerId)->delete()
        ]);
    }

    //
}
