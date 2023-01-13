<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;

class AuthService
{

    public function login( $request )
    {
        $user = '';
        $getEmail = User::where('email', $request['email'])->first();
        if ( !$getEmail ) throw ValidationException::withMessages([
            'email' => ['Email not registered.'],
        ]); 
        $user = $getEmail;           
        if ( !auth()->attempt( [ 'email' => $user->email, 'password' => $request['password'] ] ) ) {
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]); 
        }
        $user->token = $user->createToken('auth_token')->plainTextToken;
        return $user;
    }

    public function loggedIn()
    {
        $user = User::where('id',auth()->user()->id)->first();
        return $user;
    }

    public function register($request)
    {
        try {
            DB::beginTransaction();
            $user = User::create($request);
            if (!$user) {
                DB::rollBack();
                throw new Exception("Failed to create user");
            }

            DB::commit();
            $user->token = $user->createToken('MyApp')->plainTextToken;
            return $user;
        } catch (ValidationException $th) {
            throw new Exception($th->getMessage());
        }
    }
}
