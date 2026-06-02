<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\BookingsSeeder;
use Database\Seeders\FieldsSeeder;
use Database\Seeders\PaymentsSeeder;
use Database\Seeders\RolesSeeder;
use Database\Seeders\SportsSeeder;
use Database\Seeders\TimeSlotsSeeder;
use Database\Seeders\UsersSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            SportsSeeder::class,
            FieldsSeeder::class,
            TimeSlotsSeeder::class,
            BookingsSeeder::class,
            PaymentsSeeder::class,
        ]);
    }
}
