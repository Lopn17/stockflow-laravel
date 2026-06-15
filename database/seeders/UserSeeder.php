<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@stockflow.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Staff User',
            'email'    => 'staff@stockflow.test',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        User::create([
            'name'     => 'Viewer User',
            'email'    => 'viewer@stockflow.test',
            'password' => Hash::make('password'),
            'role'     => 'viewer',
        ]);
    }
}