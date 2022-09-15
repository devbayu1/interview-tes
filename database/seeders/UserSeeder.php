<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'user1@gmail.com',
            'name' => 'User 1',
            'password' => Hash::make('password'),
            'status' => '1',
        ]);
    }
}
