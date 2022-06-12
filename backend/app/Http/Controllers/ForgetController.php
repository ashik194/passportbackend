<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ForgetRequest;
use App\Models\User;
use Mail;
use App\Mail\ForgetMail;

class ForgetController extends Controller
{
    //
    public function ForgetPassword(ForgetRequest $request){
        $email = $request->email; 
        if(User::where('email', $email)->doesntExist()){
            return response([
                'message' => 'Email Invalid',
            ], 401);
        }
        $token = rand(10, 100000);

        try{
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);

            Mail::to($email)->send(new ForgetMail($token));

            return response([
                'message' => 'Reset Passwored Link send on your Email'
            ]);

        }catch(Exception $e){
            return response([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
