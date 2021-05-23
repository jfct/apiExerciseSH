<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Users;
use App\Models\UsersType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller {
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request) {

        // Validate incoming request 
        $this->validate($request, [
            'name'      => 'required|string',
            'password'  => 'required|string',
        ]);

        $credentials = $request->only(['name', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function me() {
        return auth()->user();
    }
    

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    private function register(Request $request, $usersTypeId) {
        try {

            $user               = new Users;
            $user->name         = $request->input('name');
            $user->usersTypeId  = $usersTypeId;
            $plainPassword      = $request->input('password');
            $user->password     = app('hash')->make($plainPassword);
            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            Log::error($e);
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function registerManager(Request $request) {
        //validate incoming request 
        $this->validate($request, [
            'name'      => 'required|string',
            'password'  => 'required|string',
        ]);
        return $this->register($request, UsersType::getTypeId('manager'));
    }
    public function registerTechnician(Request $request) {
        //validate incoming request 
        $this->validate($request, [
            'name'      => 'required|string',
            'password'  => 'required|string',
        ]);
        return $this->register($request, UsersType::getTypeId('technician'));
    }


}
