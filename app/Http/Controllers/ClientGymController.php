<?php

namespace App\Http\Controllers;

use App\Models\ClientGym;
use App\Models\Gym;

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
            'data' => ClientGym::where("gym_id",$gymExist->id)->get()
        ]);
    }

    //
}
