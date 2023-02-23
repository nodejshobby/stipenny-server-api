<?php

namespace App\Http\Controllers\V1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\V1\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $input['role_id'] = Role::USER;
        $input['password'] = Hash::make($input['password']);

        $newUser = User::create($input);

        if($newUser){
            return response(new UserResource($newUser), 201);
        }

        return response([
            'message' => "Error in creating user"
        ],500);
    }

    public function login(LoginRequest $request){
        $input = $request->validated();

        $user = User::where('email', $input['email'])->first();
        $passwordChecked = Hash::check($input['password'], $user->password);


        if($user && $passwordChecked){
            $token = $user->createToken('main_auth')->plainTextToken;

            return response(['token' => $token],200);
        }
        return response(["message" => "Invalid email or password"], 404);

    }
}