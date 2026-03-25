<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Guru User',
            'email' => 'guru@guru.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        User::factory()->create([
            'name' => 'Wali Santri User',
            'email' => 'walisantri@walisantri.com',
            'password' => bcrypt('password'),
            'role' => 'wali_santri',
        ]);

        $this->call(DummyDataSeeder::class);
    }
}
