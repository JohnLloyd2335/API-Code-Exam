<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

       $request->validated($request->all());

       $user = User::create([
        'username' => $request->username,
        'password' => Hash::make($request->password)
       ]);

       if(!$user)
       {
            return response()->json('Failed registration',400); 
       }

       $token = $user->createToken('Registration API Token of: '.$user->username)->plainTextToken;

       return response()->json('Successful registration, token: '.$token,201);

    }

    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        $user = User::where('username',$request->username)->first();

        if(!$user)
        {
            return response()->json([
                'User not Found'
            ],404);
        }

        $auth_attempt = Auth::attempt([
            'username' => $user->username,
            'password' => $request->password
        ]);

        if(!$auth_attempt)
        {
            return response()->json([
                'Incorrect Password'
            ],401);
        }
        elseif($auth_attempt)
        {
            $user = User::where('username',$request->username)->first();

            $token = $user->createToken('Login API Token of: '.$request->username)->plainTextToken;
    
            return response()->json([
                'Successful login, token: '.$token
            ]);
    
        }
        else
        {
            return response()->json([
                'Failed login'
            ],401);
        }

        
    }
}
