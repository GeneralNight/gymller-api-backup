<?php

namespace App\Http\Controllers\Auth;

use App\Models\Client;
use App\Models\Gym;
use App\Models\GymWorker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tymon\JWTAuth\JWTAuth;

class UserAuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth2:api2', ['except' => ['login']]);
    }

    /**
     * Login
     *
     * @return void
     */
    public function login(Request $request)
    {
        $data = $request->all();

        $client = Client::where("username",$data["username"])->first();

        if(!$client) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        if (!Hash::check($data['password'], $client['password'])) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
                'msg' => 'success',
                'me' => $client
        ]);

//        $token = $client->create('AuthToken')->accessToken;


//        $credentials = [
//            'username'=> $data["username"],
//            'password'=> $data["password"],
//        ];
//        if (!$token = auth()->attempt($credentials)) {
//            return response()->json([
//                'error' => 'Unauthorized'
//            ], 401);
//        }
//        return $this->respondWithToken($token);
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
