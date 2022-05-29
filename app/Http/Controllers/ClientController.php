<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientGym;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
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

        $allClients = Client::get();
        $clientsFiltred = [];

        for($i=0;$i<count($allClients);$i++) {
            if(!ClientGym::where([
                "client_id"=>$allClients[$i]->id,
                "gym_id"=>$gymExist->id,
                "status"=>true
            ])->first()) {
                array_push($clientsFiltred,$allClients[$i]);
            }
        }
        return response()->json([
            "msg"=> "success",
            "data"=> $clientsFiltred
        ]);
    }

    public function store(Request $request) {
        $data = $request->all();

        $clientExistCPF = Client::where("cpf",$data["cpf"])->first();
        $clientExistRG = Client::where("rg",$data["rg"])->first();
        $clientExistUser = Client::where("username",$data["username"])->first();

        if($clientExistCPF) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A client already exist in this cpf",
                "code" => 001
            ]);
        }

        if($clientExistRG) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A client already exist in this rg",
                "code" => 002
            ]);
        }

        if($clientExistUser) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A client already exist in this username",
                "code" => 003
            ]);
        }

        $data["password"] = Hash::make($data["password"]);

        return response()->json([
            "msg"=> "success",
            "data"=> Client::create($data)
        ]);
    }
}
