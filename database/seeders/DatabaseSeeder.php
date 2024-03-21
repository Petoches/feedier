<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $team = \App\Models\Team::factory()->admin()->create();

        User::factory()->team($team->id)->create([
            'name' => 'Admin',
            'email' => 'admin@feedier.com',
        ]);
    }
}
