<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return new UserResource(Auth::user());
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
        
    }

    public function register(RegisterRequest $request){
        $credentials = $request->validated();
        
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]) ;
        return new UserResource($user);
}
}