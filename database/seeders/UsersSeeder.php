<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $ownerRole = Role::where('name', 'owner')->firstOrFail();
        $customerRole = Role::where('name', 'customer')->firstOrFail();

        $users = [
            [
                'email' => 'admin@example.com',
                'role_id' => $adminRole->id,
                'name' => 'Admin User',
                'phone' => '0900000001',
                'address' => 'Ho Chi Minh City',
            ],
            [
                'email' => 'owner@example.com',
                'role_id' => $ownerRole->id,
                'name' => 'Owner User',
                'phone' => '0900000002',
                'address' => 'Da Nang City',
            ],
            [
                'email' => 'customer@example.com',
                'role_id' => $customerRole->id,
                'name' => 'Customer User',
                'phone' => '0900000003',
                'address' => 'Ha Noi City',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'role_id' => $userData['role_id'],
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'address' => $userData['address'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $user->email_verified_at = now();
            $user->save();
        }
    }
}