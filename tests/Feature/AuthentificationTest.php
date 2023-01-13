<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthentificationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $response = $this->json('POST', '/api/auth/sanctum/register', [
            'name'  =>  $name = 'Test User',
            'email'  =>  $email = time().'test@example.com',
            'password'  =>  $password = 'password',
            'role_name' => $role_name = 'guest'
        ])->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'email',
                'name',
                'token'
            ],
        ]);;

        
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function testLogin()
    {
        User::create([
            'name' => 'Test User2',
            'email'=> $email = time().'tester@example.com',
            'password' => $password = bcrypt('123456789'),
            'role_name' => $role_name = 'guest'
        ]);
        $response = $this->json('POST','/api/auth/sanctum/login',[
            'email' => $email,
            'password' => $password,
        ])->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'email',
                'name',
                'token'
            ],
        ]);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }
}
