<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = Role::updateOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::updateOrCreate(['name' => 'owner'], ['display_name' => 'Owner']);
        Role::updateOrCreate(['name' => 'customer'], ['display_name' => 'Customer']);

        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $admin->id,
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        $user->email_verified_at = now();
        $user->save();
    }
}
