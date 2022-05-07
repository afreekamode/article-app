<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    public function authenticate(Request $request)
    {
        // Do a validation for the input
        $this->validateRequest($request);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $image_link = 'https://res.cloudinary.com/getfiledata/image/upload/';
            $image_format = 'w_200,c_thumb,ar_4:4,g_face/';
            $token = $user->createToken('authToken')->accessToken;
    
            if ($user->email_verified_at != null) {
                $msg['message'] = 'Login Successful!';
                $msg['image_link'] = $image_link;
                $msg['image_small_view_format'] = $image_format;
                $msg['Accesstoken'] = $token;
                return response()->json($msg, 200);
            } else {
                $msg['success'] = false;
                $msg['message'] = 'Login Unsuccessful: account has not been confirmed yet!';
                return response()->json($msg, 401);
            }
            
        }else{
            return response()->json(['message' => 'invalid login credentials'], 422);
    }
}

    public function validateRequest(Request $request){
            $rules = [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ];
            $messages = [
                'required' => ':attribute is required',
                'email' => ':attribute not a valid format',
            ];
        $this->validate($request, $rules, $messages);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json('logout', 201);
    }
}
