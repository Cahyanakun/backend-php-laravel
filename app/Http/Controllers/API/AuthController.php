<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Resources\Auth\AuthDetailResource;
use App\Http\Resources\User\UserDetailResource;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $service;

    public function __construct( AuthService $service )
    {
        $this->service = $service;
        $this->middleware('auth:sanctum', ['except' => [ 
            'register',
            'login',
        ]]);
    }

    public function login(LoginRequest $request)
    {
        try {
            $response = $this->service->login( $request->all() );
            return $this->successResp('Successfully logged in', new AuthDetailResource($response));
        } catch (ValidationException $th) {
            return $this->errorResp($th->errors());
        }
    }

    public function loggedIn(Request $request)
    {
        try {
            $response = $this->service->loggedIn();
            return $this->successResp('Successfully logged in', new UserDetailResource($response));
        } catch (ValidationException $th) {
            return $this->errorResp($th->errors());
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $register = $this->service->register( $request->all() );
            if ( $register ) {
                return $this->successResp('Successfully register', $register);
            } else {
                return $this->errorResp('Failed register');
            }
        } catch (ValidationException $th) {
            return $this->errorResp($th->errors());
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->successResp('Successfully logged out');
    }
}
