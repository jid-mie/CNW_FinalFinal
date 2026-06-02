<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'admin' => 'Admin',
            'owner' => 'Owner',
            'customer' => 'Customer',
        ] as $name => $displayName) {
            Role::updateOrCreate(
                ['name' => $name],
                ['display_name' => $displayName]
            );
        }
    }
}