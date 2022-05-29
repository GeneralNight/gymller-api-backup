<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymPositions;
use App\Models\GymWorker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GymController extends Controller
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

        $gymExist = Gym::where("slug",$data["slug"])->first();



        if($gymExist) {
            return response()->json([
                "msg"=> "error",
                "data"=> "A gym already exist in this slug",
                "code" => 001
            ]);
        }

        $data["password"] = Hash::make($data["password"]);

        $createdGym = Gym::create($data);

        if($createdGym) {
            $gymAdminPos = GymPositions::create([
                'name' => 'admin-master',
                'gym_id' => $createdGym->id
            ]);

            if(!$gymAdminPos) {
                Gym::find($createdGym->id)->delete();
                return response()->json([
                    "msg"=> "error",
                    "data"=> "Error create gym admin pos",
                    "code" => 002
                ]);
            }

            $gymAdminUser = GymWorker::create([
                'name' => 'admin-master',
                'gym_id' => $createdGym->id,
                'cpf' => '00000000000',
                'rg' => '000000000',
                'cep' => '0000000000',
                'address' => 'none',
                'neighborhood' => 'none',
                'city' => 'none',
                'state' => 'none',
                'number' => '000',
                'email' => $data["email"],
                'salary' => 0.0,
                'phone' => '00000000000',
                'position_id' => $gymAdminPos->id,
                'username' => $data['username'],
                'password' => $data['password'],
            ]);

            if(!$gymAdminUser) {
                Gym::find($createdGym->id)->delete();
                return response()->json([
                    "msg"=> "error",
                    "data"=> "Error create gym admin user",
                    "code" => 003
                ]);
            }
        }



        return response()->json([
            "msg"=> "success",
        ]);
//        dd($gymExist);
    }

    //
}
