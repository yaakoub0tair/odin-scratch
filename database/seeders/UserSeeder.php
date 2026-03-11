<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@odin.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role' => 'admin'
            ],
            [
                'name' => 'Editor User',
                'email' => 'editor@odin.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role' => 'editor'
            ],
            [
                'name' => 'Viewer User',
                'email' => 'viewer@odin.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role' => 'viewer'
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::firstOrCreate(['email' => $userData['email']], $userData);
            $user->assignRole($role);
        }
    }
}
