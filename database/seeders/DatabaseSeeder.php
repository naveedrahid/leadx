<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'developer@pixelz360.com.au',
            'email_verified_at' => now(),
            'password' => bcrypt('admin@leadxforms'),
            'user_type' => 'admin',
            'remember_token' => Str::random(10),
            'phone_number' => '+1-202-555-0150',
            'avatar_color' => '#24336a',
            'status' => 'active',
            'is_super' => 1
        ]);
    }
}
