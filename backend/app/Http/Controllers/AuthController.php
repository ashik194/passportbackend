<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function Login(Request $request){
        try{
            if(Auth::attempt($request->only('email','password'))){
                $user = Auth::user();
                $token = $user->createToken('API Token')->accessToken;

                return response([
                    'message' => 'Successfully Login',
                    'token' => $token,
                    'user' => $user,
                ], 200);
            }
        }catch(Exception $e){
            return response([
                'message' => $e->getMessage(),
            ], 400);
        }
        return response([
            'message' => 'Invalid Email or Password',
        ], 401);
    }

    public function Register(RegisterRequest $request){
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('API Token')->accessToken;

            return response()->json([
                'message' => 'User Registered Successfully...',
                'token' => $token,
                'user' => $user,
            ], 200);
        }catch(Exception $e){
            return response([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
