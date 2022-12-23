<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileImage;
use App\Http\Requests\UpdateUserNameRequest;
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
            'image' => "public/images/".$imageName,
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

    public function getProfile(Request $request){
        return $this->sendResponse(['data' => $request->user()]);
    }

    public function updateProfileImage(UpdateProfileImage $request){
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        User::where('id',$request->user()->id)->update(['image' => "public/upload/".$imageName]);
        return $this->sendResponse(['data' => 'Update user image done']);
    }
    
    public function updateUserName(UpdateUserNameRequest $request){
        User::where('id',$request->user()->id)->update(['name' => $request->name]);
        return $this->sendResponse(['data' => 'Update name updated succsfully']);
    }


}
