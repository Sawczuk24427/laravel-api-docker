<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
