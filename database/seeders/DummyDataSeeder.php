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
        \App\Models\KelasHalaqah::truncate();
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

        $kelasOptions = ['1', '2', '3', '4', '5', '6'];
        $halaqahOptions = ['A', 'B', 'C'];

        $kelasTingkatMap = [];
        foreach ($kelasOptions as $tingkat) {
            foreach ($halaqahOptions as $huruf) {
                $kls = \App\Models\KelasHalaqah::create([
                    'nama_kelas' => $tingkat . '/' . $huruf,
                    'ustadz_id' => $faker->randomElement($ustadzIds),
                ]);
                $kelasTingkatMap[$kls->id] = (int) $tingkat;
            }
        }

        // Buat data 5 Santri per kelas
        $santriIds = [];
        $santriTingkatMap = [];
        foreach ($kelasTingkatMap as $k_id => $tingkat) {
            for ($j = 0; $j < 5; $j++) {
                $kelamin = $faker->randomElement(['Laki-laki', 'Perempuan']);
                
                $santri = Santri::create([
                    'nama_santri' => $faker->name($kelamin === 'Laki-laki' ? 'male' : 'female'),
                    'jenis_kelamin' => $kelamin,
                    'kelas_halaqah_id' => $k_id,
                    'nisn' => $faker->unique()->numerify('##########'),
                    'nama_orangtua' => $faker->name,
                    'wa_orangtua' => $faker->phoneNumber,
                ]);
                
                $santriIds[] = $santri->id;
                $santriTingkatMap[$santri->id] = $tingkat;
            }
        }

        // Buat 3 Setoran per Santri (Sabaq, Sabqi, Manzil)
        $jenisSetoran = [
            // Sabaq (hafalan baru) - ziyadah dominan
            [
                'catatan_prefix' => 'Sabaq',
                'ziyadah_baris' => [10, 20],
                'rabth_baris' => [0, 5],
                'murajaah_baris' => [0, 5],
            ],
            // Sabqi (ulangan kemarin) - rabth dominan
            [
                'catatan_prefix' => 'Sabqi',
                'ziyadah_baris' => [0, 5],
                'rabth_baris' => [10, 25],
                'murajaah_baris' => [0, 5],
            ],
            // Manzil (ulangan lama) - murajaah dominan
            [
                'catatan_prefix' => 'Manzil',
                'ziyadah_baris' => [0, 3],
                'rabth_baris' => [0, 5],
                'murajaah_baris' => [15, 40],
            ],
        ];

        foreach ($santriIds as $santriId) {
            $tingkat = $santriTingkatMap[$santriId];
            foreach ($jenisSetoran as $idx => $jenis) {
                Setoran::create([
                    'santri_id' => $santriId,
                    'tanggal' => now()->subDays($idx),
                    'kehadiran' => 'Hadir',
                    'ziyadah_juz' => rand(1, 30),
                    'ziyadah_surat' => rand(1, 114),
                    'ziyadah_ayat_mulai' => rand(1, 10),
                    'ziyadah_ayat_selesai' => rand(11, 20),
                    'ziyadah_baris' => rand($jenis['ziyadah_baris'][0], $jenis['ziyadah_baris'][1]) * $tingkat,

                    'rabth_juz' => rand(1, 30),
                    'rabth_surat' => rand(1, 114),
                    'rabth_ayat_mulai' => rand(1, 5),
                    'rabth_ayat_selesai' => rand(6, 15),
                    'rabth_baris' => rand($jenis['rabth_baris'][0], $jenis['rabth_baris'][1]) * $tingkat,

                    'murajaah_juz' => rand(1, 30),
                    'murajaah_surat' => rand(1, 114),
                    'murajaah_ayat_mulai' => rand(1, 5),
                    'murajaah_ayat_selesai' => rand(6, 15),
                    'murajaah_baris' => rand($jenis['murajaah_baris'][0], $jenis['murajaah_baris'][1]) * $tingkat,

                    'nilai_kelancaran' => rand(75, 100),
                    'catatan' => $jenis['catatan_prefix'] . ': ' . $faker->randomElement([
                        'Alhamdulillah lancar',
                        'Perlu diulang bagian akhir',
                        'Tajwid perlu diperhatikan',
                        'Sangat baik',
                        'Cukup lancar',
                    ]),
                ]);
            }
        }
    }
}
