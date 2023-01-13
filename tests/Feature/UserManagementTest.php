<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function authenticate()
    {
        $users = '';
        $user = User::create([
            'name' => 'test',
            'email' => rand(12345,678910).'test@gmail.com',
            'password' =>  '123456789',
            'role_name' => 'admin'
        ]);

        $getEmail = User::where('email', $user->email)->first();
        $users = $getEmail;           
        if ( !auth()->attempt( [ 'email' => $user->email, 'password' => '123456789' ] ) ) {
            return response(['message' => 'Login credentials are invaild']);
        }
        $token = $users->createToken('auth_token')->plainTextToken;
        return $accessToken = $token;
    }

    protected function letstUserId()
    {
        $getUser = User::orderBy('id','desc')->first();
        return $getUser->id;
    }

    public function testgetAllUser()
    {   
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => $token,
        ])->json('GET','/api/user');
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }   

    public function testCreateUser()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => $token,
        ])->json('POST','/api/user',[
            'name' => 'Test Name',
            'email' => rand(12345,678910).'test@gmail.com',
            'role_name' => 'guest',
            'password' => 'password',
        ]);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function testUpdateUser()
    {
        $token = $this->authenticate();
        $id = $this->letstUserId();
        $response = $this->withHeaders([
            'Authorization' => $token,
        ])->json('PUT','/api/user/'.$id,[
            'name' => 'Test Name Update',
            'email' => rand(12345,678910).'test@gmail.com',
            'role_name' => 'guest',
            'password' => 'password',
        ]);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function testShowUser()
    {
        $token = $this->authenticate();
        $id = $this->letstUserId();
        $response = $this->withHeaders([
            'Authorization' => $token,
        ])->json('GET','/api/user/'.$id);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function testDeleteUser()
    {
        $token = $this->authenticate();
        $id = $this->letstUserId();
        $response = $this->withHeaders([
            'Authorization' => $token,
        ])->json('DELETE','/api/user/'.$id);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }
}
