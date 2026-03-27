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

        // Buat setoran harian selama 30 hari terakhir (Sabaq, Sabqi, Manzil per hari)
        foreach ($santriIds as $santriId) {
            $tingkat = $santriTingkatMap[$santriId];
            
            for ($day = 0; $day < 30; $day++) {
                $tanggal = now()->subDays($day);
                
                // Sabaq (hafalan baru) - variasi naik turun
                Setoran::create([
                    'santri_id' => $santriId,
                    'tanggal' => $tanggal,
                    'kehadiran' => $faker->randomElement(['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin']),
                    'ziyadah_juz' => rand(1, min(30, $tingkat * 5)),
                    'ziyadah_surat' => rand(1, 114),
                    'ziyadah_ayat_mulai' => rand(1, 10),
                    'ziyadah_ayat_selesai' => rand(11, 20),
                    'ziyadah_baris' => rand(5, 15) * $tingkat + rand(-3, 5),
                    'rabth_juz' => rand(1, 30),
                    'rabth_surat' => rand(1, 114),
                    'rabth_ayat_mulai' => rand(1, 5),
                    'rabth_ayat_selesai' => rand(6, 15),
                    'rabth_baris' => rand(0, 3) * $tingkat,
                    'murajaah_juz' => rand(1, 30),
                    'murajaah_surat' => rand(1, 114),
                    'murajaah_ayat_mulai' => rand(1, 5),
                    'murajaah_ayat_selesai' => rand(6, 15),
                    'murajaah_baris' => rand(0, 3) * $tingkat,
                    'nilai_kelancaran' => rand(75, 100),
                    'catatan' => 'Sabaq: ' . $faker->randomElement([
                        'Alhamdulillah lancar', 'Perlu diulang', 'Tajwid diperhatikan', 'Sangat baik', 'Cukup lancar',
                    ]),
                ]);

                // Sabqi (ulangan kemarin)
                Setoran::create([
                    'santri_id' => $santriId,
                    'tanggal' => $tanggal,
                    'kehadiran' => 'Hadir',
                    'ziyadah_juz' => rand(1, 30),
                    'ziyadah_surat' => rand(1, 114),
                    'ziyadah_ayat_mulai' => rand(1, 10),
                    'ziyadah_ayat_selesai' => rand(11, 20),
                    'ziyadah_baris' => rand(0, 3) * $tingkat,
                    'rabth_juz' => rand(1, 30),
                    'rabth_surat' => rand(1, 114),
                    'rabth_ayat_mulai' => rand(1, 5),
                    'rabth_ayat_selesai' => rand(6, 15),
                    'rabth_baris' => rand(8, 20) * $tingkat + rand(-2, 4),
                    'murajaah_juz' => rand(1, 30),
                    'murajaah_surat' => rand(1, 114),
                    'murajaah_ayat_mulai' => rand(1, 5),
                    'murajaah_ayat_selesai' => rand(6, 15),
                    'murajaah_baris' => rand(0, 3) * $tingkat,
                    'nilai_kelancaran' => rand(70, 100),
                    'catatan' => 'Sabqi: ' . $faker->randomElement([
                        'Alhamdulillah lancar', 'Perlu diulang', 'Tajwid diperhatikan', 'Sangat baik', 'Cukup lancar',
                    ]),
                ]);

                // Manzil (ulangan lama)
                Setoran::create([
                    'santri_id' => $santriId,
                    'tanggal' => $tanggal,
                    'kehadiran' => 'Hadir',
                    'ziyadah_juz' => rand(1, 30),
                    'ziyadah_surat' => rand(1, 114),
                    'ziyadah_ayat_mulai' => rand(1, 10),
                    'ziyadah_ayat_selesai' => rand(11, 20),
                    'ziyadah_baris' => rand(0, 2) * $tingkat,
                    'rabth_juz' => rand(1, 30),
                    'rabth_surat' => rand(1, 114),
                    'rabth_ayat_mulai' => rand(1, 5),
                    'rabth_ayat_selesai' => rand(6, 15),
                    'rabth_baris' => rand(0, 3) * $tingkat,
                    'murajaah_juz' => rand(1, 30),
                    'murajaah_surat' => rand(1, 114),
                    'murajaah_ayat_mulai' => rand(1, 5),
                    'murajaah_ayat_selesai' => rand(6, 15),
                    'murajaah_baris' => rand(10, 30) * $tingkat + rand(-5, 8),
                    'nilai_kelancaran' => rand(65, 100),
                    'catatan' => 'Manzil: ' . $faker->randomElement([
                        'Alhamdulillah lancar', 'Perlu diulang', 'Tajwid diperhatikan', 'Sangat baik', 'Cukup lancar',
                    ]),
                ]);
            }
        }
    }
}
