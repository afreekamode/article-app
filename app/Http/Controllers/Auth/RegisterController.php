<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\welcomeMail;
use App\Models\Bank;
use Hamcrest\Core\HasToString;
use Ramsey\Uuid\Lazy\LazyUuidFromString;

class RegisterController extends Controller
{

    
     // creating user's account
    public function createUser(Request $request)
    {
        $this->validateRequest($request);
        $verifycode = Str::random(6);
        $user = User::create([
            'name'    => $request->input('name'),
            'email'    => $request->input('email'),
            'image'    => 'no_image.jpg',
            'password' => Hash::make($request->input('password')),
            'phone'    => $request->input('phone'),
            'verifycode' => $verifycode
        ]);
        $msg['message'] = 'A otp code has been sent to your email, please use it to veriify your account';
        $msg['otp']    = $verifycode;
        $msg['status'] = 201;

        //Send a mail form account verification
        // Mail::to($user->email)->send(new welcomeMail($user));
        return $msg;
    }


    public function validateRequest(Request $request){
            $rules = [
                'email'    => 'sometimes|email|unique:users',
                'password' => 'required|min:8',
                'phone'    => 'required|digits_between:10,12'
            ];
            $messages = [
                'required' => ':attribute is required',
                'email'    => ':attribute not a valid format',
            ];
        $this->validate($request, $rules, $messages);
    }

}
