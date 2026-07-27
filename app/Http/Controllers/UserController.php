<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return UserResource::collection($users);
    }

    public function show(User $user){
        
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request){
        $validated = $request->validated();
        $user = User::create($validated);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user){
        $user -> update($request->validated());
        return new UserResource($user);
    }

    public function destroy(User $user){
        $user -> delete();
        return response()->noContent();
    }
}
