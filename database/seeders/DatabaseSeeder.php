<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Avoid duplicate user creation by first checking if the user exists
        User::firstOrCreate(
            ['email' => 'test@admin.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // Seed currencies
        $this->call([
            CurrencySeeder::class,
        ]);
    }
}
