<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;



class PassportAuthController extends Controller
{
     /**
     * Registration
     */
    public function register(RegisterRequest $request)
    {

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image' => $imageName,
        ]);
       
        $token = $user->createToken('LaravelAuthApp')->accessToken;
        return $this->sendResponse(['user' => $user , 'token' => $token]);
    }
 
    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->check($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            $user = User::where("email", "=", $request->email)->first();
            return $this->sendResponse(['user' => $user , 'token' => $token]);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
