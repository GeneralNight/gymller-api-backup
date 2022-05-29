<?php

namespace App\Http\Controllers\Auth;

use App\Models\Gym;
use App\Models\GymWorker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Login
     *
     * @return void
     */
    public function login(Request $request)
    {
        $data = $request->all();
//        $gym = Gym::where("slug",$data['slug'])->first();
//
//        if(!$gym) {
//            return response()->json([
//                'message'=>'invalid'
//            ]);
//        }
//
//        $user = GymWorker::where([
//            'gym_id' => $gym->id,
//            'username' => $data['username'],
//            'password' => $data['password'],
//        ])->first();
//
//        if(!$user) {
//            return response()->json([
//                'message'=>'invalid'
//            ]);
//        }
//        return response()->json([
//            'message'=> 'success'
//        ]);

        $gym = Gym::where("slug",$data["slug"])->first();

        if(!$gym) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $data["gym_id"] = $gym->id;

        $credentials = [
            'username'=> $data["username"],
            'password'=> $data["password"],
            'gym_id'=> $data["gym_id"]
        ];
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * refresh
     *
     * @return void
     */
    public function refresh()
    {
        $token = auth()->refresh();
        return $this->respondWithToken($token);
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param [type] $token
     * @return void
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600, // default 1 hour
            'me' => auth()->user()
        ]);
    }
}
