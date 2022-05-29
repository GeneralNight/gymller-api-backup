<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

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

        return response()->json([
            "msg"=> "success",
            "data"=> Client::create($data)
        ]);
    }
}
