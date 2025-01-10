<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 4,
                'name' => 'Akshay Dev',
                'email' => 'akkidevdo@examplegmail.com',
                'password' => bcrypt('12345678'), // Removed 'value:' as it's unnecessary
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'user'
            ],
            [
                'id' => 5,
                'name' => 'Ram Dev',
                'email' => 'ram@gmail.com',
                'password' => bcrypt('password123'),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'user'
            ],
            [
                'id' => 6,
                'name' => 'Kerav DevSmithD',
                'email' => 'kerav@gmail.com',
                'password' => bcrypt('mypassword'),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'user'
            ],
        ]);
    }
}