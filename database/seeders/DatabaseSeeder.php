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

        // Buat 6 user wali santri, 1 per tingkat kelas
        for ($tingkat = 1; $tingkat <= 6; $tingkat++) {
            User::factory()->create([
                'name' => "Wali Santri Kelas {$tingkat}",
                'email' => "walisantri{$tingkat}@walisantri.com",
                'password' => bcrypt('password'),
                'role' => 'wali_santri',
                'kelas_tingkat' => $tingkat,
            ]);
        }

        $this->call(DummyDataSeeder::class);

        // Assign guru ke ustadz pertama setelah data ustadz dibuat
        $firstUstadz = \App\Models\Ustadz::first();
        if ($firstUstadz) {
            $guruUser = User::where('email', 'guru@guru.com')->first();
            $guruUser->update([
                'name' => $firstUstadz->nama_ustadz,
                'ustadz_id' => $firstUstadz->id,
            ]);
        }
    }
}
