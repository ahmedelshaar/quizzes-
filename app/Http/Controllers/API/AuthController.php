<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(LoginRequest $request) {
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response([
            'user' => $user,
            'token' => $user->createToken(env('APP_KEY'))->plainTextToken
        ], 201);
    }

    public function user(){
        return auth()->user();
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function update(UserUpdateRequest $request){
        $user = auth()->user();
        if($request->has('image')){
            $filename = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $filename);
            $user->image = $filename;
        }
        if($request->has('password')){
            $password = Hash::make($request->password);
            $user->password = $password;
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->save();
        return response([
            'message' => 'تم تحديث البيانات بنجاح',
            'data' => auth()->user()
        ]);
    }

}
