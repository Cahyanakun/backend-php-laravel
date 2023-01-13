<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;

class ManagementArticleTest extends TestCase
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
        $users->token = $token;
        return $access = $users;
    }

    protected function latestArticleId()
    {
        $getAticle = Article::orderBy('id','desc')->first();
        return $getAticle->id;
    }

    public function testgetAllArticle()
    {   
        $user = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => $user->token,
        ])->json('GET','/api/article');
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }   

    public function testCreateArticle()
    {
        $user = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => $user->token,
        ])->json('POST','/api/article',[
            "title" =>  "Lorem Ipsum",
            "description" =>  "Lorem Ipsum is simply dummy text of",  
            "publish" => true,
            "user_id" => $user->id
        ]);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function testUpdateArticle()
    {
        $user = $this->authenticate();
        $id = $this->latestArticleId();
        $response = $this->withHeaders([
            'Authorization' => $user->token,
        ])->json('PUT','/api/article/'.$id,[
            "title" =>  "Lorem Ipsum",
            "description" =>  "Lorem Ipsum is simply dummy text of",  
            "publish" => true,
            "user_id" => $user->id
        ]);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function testShowArticle()
    {
        $user = $this->authenticate();
        $id = $this->latestArticleId();
        $response = $this->withHeaders([
            'Authorization' => $user->token,
        ])->json('GET','/api/article/'.$id);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function testDeleteArticle()
    {
        $user = $this->authenticate();
        $id = $this->latestArticleId();
        $response = $this->withHeaders([
            'Authorization' => $user->token,
        ])->json('DELETE','/api/article/'.$id);
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }
}
