<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class GenerateUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $user = [
            [
                'name' => 'Admin',
                'email' => 'admin@mailinator.com',
                'password' => bcrypt('password'),
                'role_name' => 'admin',
                'id' => 1,
            ],
            [
                'name' => 'Author 1',
                'email' => 'author1@mailinator.com',
                'password' => bcrypt('password'),
                'role_name' => 'author',
                'id' => 2,
            ],
            [
                'name' => 'Author 2',
                'email' => 'author2@mailinator.com',
                'password' => bcrypt('password'),
                'role_name' => 'author',
                'id' => 3,
            ],
            [
                'name' => 'Guset',
                'email' => 'guest@mailinator.com',
                'password' => bcrypt('password'),
                'role_name' => 'guest',
                'id' => 4,
            ],
        ];

        foreach( $user as $users ) {
            User::create([
                'id' => $users['id'], 
                'name' => $users['name'],
                'email' => $users['email'],
                'password' => $users['password'],
                'role_name' => $users['role_name']
            ]);
        }
    }
}
