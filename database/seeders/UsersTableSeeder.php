<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'usertype' => 'admin',
            'phone' => '+591 67927794',
            'address' => 'tomave',
            'position' => 'Administrador',
        ]);

        // Usuario con rol User
        User::create([
            'name' => 'User',
            'username' => 'user',
            'password' => Hash::make('password'),
            'usertype' => 'user',
            'phone' => '+591 67835407',
            'address' => 'tica tica',
            'position' => 'Usuario',
        ]);
    }
}
