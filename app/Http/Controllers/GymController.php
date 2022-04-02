<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\Request;
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

        return response()->json([
            "msg"=> "success",
            "data"=> Gym::create($data)
        ]);
//        dd($gymExist);
    }

    //
}
