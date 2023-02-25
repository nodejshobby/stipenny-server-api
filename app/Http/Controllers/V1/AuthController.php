<?php

namespace App\Http\Controllers\V1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Http\Requests\V1\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $input['role_id'] = Role::USER;
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        if($user){
            $user->detail()->create([
                'phone_number' => $input['phone_number'],
            ]);

            $user->sendEmailVerificationNotification();
            
            return response(new UserResource($user), 201);
        }

        Log::critical("Cannot create a new user due to some errors");
        return response([
            'message' => "Error in creating user"
        ],500);
    }

    public function login(LoginRequest $request){
        $input = $request->validated();
        $userId = $input['userId'];

        $user = User::where('email', $userId)->whereHas('detail')->orWhereHas('detail', function ($query) use($userId) {
            $query->where('phone_number', $userId);
        })->first();
        
        if($user){
            $passwordChecked = Hash::check($input['password'], $user->password);
            if($passwordChecked){
                 $token = $user->createToken('main_auth')->plainTextToken;

                Log::info("Token was successfully created for user id ".$user->id);
                return response(['token' => $token],200);
            }

            Log::info("An anonymous user had a failed login");
            return response(["message" => "Invalid email or password"], 404);
           
        }

        Log::info("An anonymous user had a failed login");
        return response(["message" => "Invalid email or password"], 404);

    }


    public function verify($id, Request $request){
        if (!$request->hasValidSignature()) {

            Log::info("An anonymous user tried expired or invalid verification link");

            return response(["message" => "Invalid/Expired url provided"], 401);
        }

        $user = User::findOrFail($id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response(["message" => "Email is verified successfully"], 200);
    }

    public function resend(Request $request){

        if (auth()->user()->hasVerifiedEmail()) {
            return response(["message" => "Email already verified"], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response(["message" => "Verification link is sent"], 200);
    }

    public function forgot(Request $request) {
        $credentials = $request->validate(['email' => 'required|email']);

        $user  = User::where('email', $credentials['email'])->where('role_id', Role::USER)->first();

        if($user){
            Password::sendResetLink($credentials);
            return response(["message" => 'Reset password link sent to your email'], 200);
        }

        Log::info("An anonymous user tries to request forgot password of non real user");
        return response(["message" => 'Reset password link sent to your email'], 200);   
     }

     public function reset(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => ['required', PasswordRule::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),'confirmed']
        ]);

        $credentials['role_id'] = Role::USER;

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status === Password::INVALID_TOKEN) {
            Log::info("An anonymous user tries an invalid or expired token password reset");
            return response(["message" => "Invalid token provided"], 400);
        }

        return response(["message" => "Password has been successfully changed"], 200);
    }
}