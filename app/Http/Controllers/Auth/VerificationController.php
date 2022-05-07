<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
      //generate new password for the user
    public function generatedPassword()
    {
        return substr(md5(time()), 0, 6);
    }

    public function verify($verifycode, $email) {

        $checkCode = User::where('verifycode', $verifycode)
        ->where('email', $email)
        ->exists();

        if ($checkCode) {

        $user = User::where('verifycode', $verifycode)->get()->first();

            $token = $user->createToken('authToken')->accessToken;
        
            if ($user->email_verified_at == null){
                //generate a new verify code 
                $user->verifycode = $this->generatedPassword();
                $user->email_verified_at = now();
                $user->save();
                
                $msg["message"] = "Account is verified. You can now login.";
                $msg['verified'] = "True";
                $msg['Bearer_token'] = $token;
                return response()->json($msg, 200);
            }else{
                $msg["message"] = "Account verified already. Please Login";
                $msg['note'] = 'please redirect to login page';
                $msg['verified'] = "Done";
                return response()->json($msg, 208);
             }

        } else{
            $msg["message"] = "Account with code does not exist!";

            return response()->json($msg, 404);

        }  
        
    }

}
