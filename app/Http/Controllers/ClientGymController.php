<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientGym;
use App\Models\Gym;
use http\Env\Response;

class ClientGymController extends Controller
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
            'msg' => 'success',
            'data' => ClientGym::where(["gym_id"=>$gymExist->id,"status"=>true])->join("clients","clients.id","=","client_gyms.client_id")->get()
        ]);
    }

    public function store($slug,$studentId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $student = Client::where("id",$studentId)->first();

        if(!$student) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any student with this id",
                "code"=> "002"
            ]);
        }

        $dataToSave = [
            "gym_id" => $gymExist->id,
            "client_id" => $student->id,
            "status" => true
        ];

        return response()->json([
            'msg' => 'success',
            'data' => ClientGym::create($dataToSave)
        ]);
    }

    public function delete($slug,$studentId) {
        $gymExist = Gym::where("slug",$slug)->first();

        if(!$gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any gym with this id",
                "code"=> "001"
            ]);
        }

        $student = Client::where("id",$studentId)->first();

        if(!$student) {
            return response()->json([
                "msg"=> "error",
                "data"=> "There's any student with this id",
                "code"=> "002"
            ]);
        }

        return response()->json([
            "msg"=> "success",
            "data"=> ClientGym::where([
                "client_id" => $student->id,
                "gym_id" => $gymExist->id
            ])->update(['status'=>false]),
        ]) ;
    }

    //
}
