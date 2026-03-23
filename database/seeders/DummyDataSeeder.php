<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Ustadz;
use App\Models\Santri;
use App\Models\Setoran;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Setoran::truncate();
        Santri::truncate();
        Ustadz::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $faker = Faker::create('id_ID');

        // Buat data 10 Ustadz
        $ustadzIds = [];
        for ($i = 0; $i < 10; $i++) {
            $kelamin = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $nama = $kelamin === 'Laki-laki' 
                ? 'Ust. ' . $faker->firstNameMale . ' ' . $faker->lastName
                : 'Usth. ' . $faker->firstNameFemale . ' ' . $faker->lastName;

            $ustadz = Ustadz::create([
                'nama_ustadz' => $nama,
                'jenis_kelamin' => $kelamin,
                'no_wa' => $faker->phoneNumber,
                'asal_pondok' => 'Pondok Pesantren ' . $faker->city,
            ]);
            
            $ustadzIds[] = $ustadz->id;
        }

        // Buat data 10 Santri
        $kelasOptions = ['1', '2', '3', '4', '5', '6'];
        $halaqahOptions = ['Abu Bakar', 'Umar', 'Utsman', 'Ali', 'Zaid', 'Saudangkala'];

        $santriIds = [];
        for ($j = 0; $j < 10; $j++) {
            $kelamin = $faker->randomElement(['Laki-laki', 'Perempuan']);
            
            $santri = Santri::create([
                'nama_santri' => $faker->name($kelamin === 'Laki-laki' ? 'male' : 'female'),
                'jenis_kelamin' => $kelamin,
                'kelas' => $faker->randomElement($kelasOptions),
                'kelas_halaqah' => $faker->randomElement($halaqahOptions),
                'nisn' => $faker->numerify('##########'),
                'ustadz_id' => $faker->randomElement($ustadzIds),
                'nama_orangtua' => $faker->name,
                'wa_orangtua' => $faker->phoneNumber,
            ]);
            
            $santriIds[] = $santri->id;
        }

        // Buat persis 1 Setoran Dummy untuk tiap Santri (Total 10 Setoran)
        foreach ($santriIds as $santriId) {
            Setoran::create([
                'santri_id' => $santriId,
                'tanggal' => now(),
                'kehadiran' => 'Hadir',
                'ziyadah_juz' => rand(1, 30),
                'ziyadah_surat' => rand(1, 114),
                'ziyadah_ayat_mulai' => rand(1, 10),
                'ziyadah_ayat_selesai' => rand(11, 20),
                'ziyadah_baris' => rand(5, 15),
                
                'rabth_juz' => rand(1, 30),
                'rabth_surat' => rand(1, 114),
                'rabth_ayat_mulai' => rand(1, 5),
                'rabth_ayat_selesai' => rand(6, 15),
                'rabth_baris' => rand(5, 15),

                'murajaah_juz' => rand(1, 30),
                'murajaah_surat' => rand(1, 114),
                'murajaah_ayat_mulai' => rand(1, 5),
                'murajaah_ayat_selesai' => rand(6, 15),
                'murajaah_baris' => rand(10, 30),

                'nilai_kelancaran' => rand(80, 100),
                'catatan' => $faker->randomElement(['', 'Ahamdulillah lancar', 'Perlu diulang bagian akhir', 'Tajwid diperhatikan']),
            ]);
        }
    }
}
