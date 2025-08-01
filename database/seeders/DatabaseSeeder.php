<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\WaliKelas;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


    public function run(): void
    {
        // Ambil mapping kode_guru => id



        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678')
        ]);

        DB::table('guru')->insert([
            ['kode_guru' => '1234567890', 'nama_guru' => 'Budi Santoso', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '0987654321', 'nama_guru' => 'Ani Setiawati', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '0011223344', 'nama_guru' => 'Dewi Lestari', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '5566778899', 'nama_guru' => 'Samsul Arifin', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '6677889900', 'nama_guru' => 'Rahmat Hidayat', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '1122334455', 'nama_guru' => 'Rafi Suyoso', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('user_guru')->insert([
            ['guru_id' => 1, 'email' => 'budisantoso@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['guru_id' => 2, 'email' => 'budisantoso1@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['guru_id' => 3, 'email' => 'soraxora@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()]
        ]);


        //seeder wali kelas
        $waliData = [
            ['kode' => '1234567890', 'nama' => 'Budi Santoso',     'password' => 'password1'],
            ['kode' => '0987654321', 'nama' => 'Ani Setiawati',    'password' => 'password2'],
            ['kode' => '1122334455', 'nama' => 'Dewi Lestari',     'password' => 'password3'],
            ['kode' => '5566778899', 'nama' => 'Samsul Arifin',    'password' => 'password4'],
            ['kode' => '6677889900', 'nama' => 'Rahmat Hidayat',   'password' => 'password5'],
        ];

        foreach ($waliData as $data) {
            $guru = Guru::where('kode_guru', $data['kode'])->first();

            if ($guru) {
                WaliKelas::updateOrCreate(
                    ['id_guru' => $guru->id_guru],
                    [
                        'kode_wali'  => $data['kode'],
                        'nama_wali'  => $data['nama'],
                        'password'   => Hash::make($data['password']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $this->command->info(" Wali Kelas untuk {$data['nama']} berhasil dibuat.");
            } else {
                $this->command->warn("Guru dengan kode {$data['kode']} tidak ditemukan. Wali tidak dibuat.");
            }
        }

        $this->command->info(" Seeder wali_kelas selesai dijalankan.");


        //seeder kelas
        $now = Carbon::now();

        DB::table('kelas')->insert([
            [
                'id_wali_kelas' => 1,
                'kode_kelas' => 'XIPA1',
                'kompetensi_keahlian' => 'IPA',
                'nama_kelas' => 'X IPA 1',
                'tingkat_kelas' => 'X',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_wali_kelas' => 1,
                'kode_kelas' => 'XIPA2',
                'kompetensi_keahlian' => 'IPA',
                'nama_kelas' => 'X IPA 2',
                'tingkat_kelas' => 'X',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_wali_kelas' => 2,
                'kode_kelas' => 'XIPS1',
                'kompetensi_keahlian' => 'IPS',
                'nama_kelas' => 'X IPS 1',
                'tingkat_kelas' => 'X',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_wali_kelas' => 2,
                'kode_kelas' => 'XIPS2',
                'kompetensi_keahlian' => 'IPS',
                'nama_kelas' => 'X IPS 2',
                'tingkat_kelas' => 'X',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_wali_kelas' => 3,
                'kode_kelas' => 'XIIPA1',
                'kompetensi_keahlian' => 'IPA',
                'nama_kelas' => 'XI IPA 1',
                'tingkat_kelas' => 'XI',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_wali_kelas' => 3,
                'kode_kelas' => 'XIIPA2',
                'kompetensi_keahlian' => 'IPA',
                'nama_kelas' => 'XI IPA 2',
                'tingkat_kelas' => 'XI',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_wali_kelas' => 4,
                'kode_kelas' => 'XIIPS1',
                'kompetensi_keahlian' => 'IPS',
                'nama_kelas' => 'XI IPS 1',
                'tingkat_kelas' => 'XI',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_wali_kelas' => 5,
                'kode_kelas' => 'XIITM1',
                'kompetensi_keahlian' => 'TM',
                'nama_kelas' => 'XII TM 1',
                'tingkat_kelas' => 'XII',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Seeder untuk tabel siswa
        DB::table('siswa')->insert([
            [
                'id_kelas' => 1,
                'nama_siswa' => 'Andi Saputra',
                'no_induk' => '123456789012',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 2,
                'nama_siswa' => 'Superman Santoso',
                'no_induk' => '234567890123',
                'id_kelas' => 2,
                'nama_siswa' => 'Alex Santoso',
                // 'nama_kelas' => 'X IPA 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 3,
                'no_induk' => '345678901234',
                'nama_siswa' => 'Citra Ayu',
                //'nama_kelas' => 'X IPS 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 4,
                'no_induk' => '456789012345',
                'nama_siswa' => 'Dedi Kusuma',
                // 'nama_kelas' => 'X IPS 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 5,
                'no_induk' => '567890123456',
                'nama_siswa' => 'Eka Putri',
                // 'nama_kelas' => 'XI IPA 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 6,
                'no_induk' => '678901234567',
                'nama_siswa' => 'Fajar Hidayat',
                // 'nama_kelas' => 'XI IPA 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 7,
                'no_induk' => '789012345678',
                'nama_siswa' => 'Gina Larasati',
                //  'nama_kelas' => 'XI IPS 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 1,
                'no_induk' => '890123456789',
                'nama_siswa' => 'Hadi Pratama',
                //  'nama_kelas' => 'X IPA 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 2,
                'no_induk' => '901234567890',
                'nama_siswa' => 'Indah Safitri',
                // 'nama_kelas' => 'X IPA 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kelas' => 3,
                'no_induk' => '012345678901',
                'nama_siswa' => 'Joko Wahyu',
                // 'nama_kelas' => 'X IPS 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... lanjutkan lainnya
        ]);



        // Seeder untuk tabel mapel
        DB::table('mapel')->insert([
            ['kode_mapel' => '101', 'nama_diklat' => 'Matematika', 'id_guru' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => '102', 'nama_diklat' => 'Fisika',     'id_guru' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => '103', 'nama_diklat' => 'Kimia',      'id_guru' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => '104', 'nama_diklat' => 'Biologi',    'id_guru' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => '105', 'nama_diklat' => 'Sejarah',    'id_guru' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => '106', 'nama_diklat' => 'B.Inggris',    'id_guru' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);


        // Seeder untuk tabel tugas
        $mapels = DB::table('mapel')->get();

        // foreach ($mapels as $mapel) {
        //     for ($i = 1; $i <= $mapel->total_tugas; $i++) {
        //         DB::table('tugas')->insert([
        //             'id_siswa' => rand(1, 5),  // Assign a random siswa
        //             'id_mapel' => $mapel->id_mapel,
        //             'nama_tugas' => $mapel->nama_diklat . ' Tugas ' . $i,
        //             'Tanggal_pengumpulan' => now()->addDays(rand(7, 14))->toDateString(),  // Random due date between 7 and 14 days
        //             'keterangan' => 'Tugas nomor ' . $i . ' untuk ' . $mapel->nama_diklat,
        //             'status' => $i % 2 == 0 ? 'Selesai' : 'Belum Selesai',  // Random status
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }

        $gurumapel = DB::table('guru')->get();

        foreach ($mapels as $mapel) {
            foreach ($gurumapel as $gm) {

                DB::table('guru_mapel')->insert([
                    'id_guru' => $gm->id_guru,
                    'kode_guru' => $gm->kode_guru,
                    'kode_mapel' => $mapel->id_mapel,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('tugas_mengajar')->insert([
            ['id_mengajar' => 1, 'id_kelas' => 1, 'id_mapel' => 1, 'id_guru' => 1, 'kode_guru' => '1234567890', 'nama_guru' => 'Budi Santoso', 'kelas' => 'X IPA 1', 'mata_diklat' => 'Matematika', 'kompetensi_keahlian' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 2,  'id_kelas' => 2, 'id_mapel' => 2, 'id_guru' => 2, 'kode_guru' => '0987654321', 'nama_guru' => 'Ani Setiawati', 'kelas' => 'X IPA 2', 'mata_diklat' => 'Fisika', 'kompetensi_keahlian' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 3,  'id_kelas' => 3, 'id_mapel' => 3, 'id_guru' => 3, 'kode_guru' => '1122334455', 'nama_guru' => 'Dewi Lestari', 'kelas' => 'X IPS 1', 'mata_diklat' => 'Kimia', 'kompetensi_keahlian' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 4,  'id_kelas' => 4, 'id_mapel' => 4, 'id_guru' => 4, 'kode_guru' => '5566778899', 'nama_guru' => 'Samsul Arifin', 'kelas' => 'X IPS 2', 'mata_diklat' => 'Biologi', 'kompetensi_keahlian' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 5,  'id_kelas' => 5, 'id_mapel' => 5, 'id_guru' => 5, 'kode_guru' => '6677889900', 'nama_guru' => 'Rahmat Hidayat', 'kelas' => 'XI IPA 1', 'mata_diklat' => 'Sejarah', 'kompetensi_keahlian' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
        ]);


        DB::table('perwalian_kelas')->insert([
            ['id_wali_kelas' => 1, 'id_kelas' => 1,  'kelas' => 'X IPA 1', 'kompetensi_keahlian' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 2, 'id_kelas' => 2, 'kelas' => 'X IPA 2', 'kompetensi_keahlian' => 'IPA',  'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 3, 'id_kelas' => 3, 'kelas' => 'X IPS 1', 'kompetensi_keahlian' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 4, 'id_kelas' => 4, 'kelas' => 'X IPS 2', 'kompetensi_keahlian' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 5, 'id_kelas' => 5, 'kelas' => 'XI IPA 1', 'kompetensi_keahlian' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $waliKelasIds = DB::table('wali_kelas')->pluck('id_wali_kelas')->toArray();

        DB::table('rekap_kelas')->insert([
            [
                'id_kelas' => 1,
                'id_mapel' => 1,
                'id_guru' => 1,
                'id_wali_kelas' => $waliKelasIds[0] ?? 1,
                'total_tugas' => 2,
                'jumlah_selesai' => 2,
                'jumlah_tanggungan' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 2,
                'id_mapel' => 2,
                'id_guru' => 2,
                'id_wali_kelas' => $waliKelasIds[1] ?? 2,
                'total_tugas' => 2,
                'jumlah_selesai' => 1,
                'jumlah_tanggungan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 3,
                'id_mapel' => 3,
                'id_guru' => 3,
                'id_wali_kelas' => $waliKelasIds[2] ?? 3,
                'total_tugas' => 2,
                'jumlah_selesai' => 2,
                'jumlah_tanggungan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 4,
                'id_mapel' => 4,
                'id_guru' => 4,
                'id_wali_kelas' => $waliKelasIds[3] ?? 4,
                'total_tugas' => 2,
                'jumlah_selesai' => 2,
                'jumlah_tanggungan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 5,
                'id_mapel' => 5,
                'id_guru' => 5,
                'id_wali_kelas' => $waliKelasIds[4] ?? 5,
                'total_tugas' => 2,
                'jumlah_selesai' => 1,
                'jumlah_tanggungan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 5,
                'id_mapel' => 6,
                'id_guru' => 6,
                'id_wali_kelas' => $waliKelasIds[4] ?? 5,
                'total_tugas' => 0,
                'jumlah_selesai' => 0,
                'jumlah_tanggungan' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);



        DB::table('rekap_pengumpulan')->insert([
            [
                'id_rekap_kelas' => 1,
                'id_siswa' => 1,
                'id_mapel' => 1,
                'nama_tugas' => 'Tugas Matematika 1',
                'tanggal_pengumpulan' => now()->addDays(3)->toDateString(),
                'nilai' => 85,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rekap_kelas' => 1,
                'id_siswa' => 1,
                'id_mapel' => 1,
                'nama_tugas' => 'Tugas Matematika 2',
                'tanggal_pengumpulan' => now()->addDays(6)->toDateString(),
                'nilai' => 90,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_rekap_kelas' => 2,
                'id_siswa' => 2,
                'id_mapel' => 2,
                'nama_tugas' => 'Tugas Fisika 1',
                'tanggal_pengumpulan' => now()->addDays(5)->toDateString(),
                'nilai' => 0,
                'status' => 'Belum Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rekap_kelas' => 2,
                'id_siswa' => 2,
                'id_mapel' => 2,
                'nama_tugas' => 'Tugas Fisika 2',
                'tanggal_pengumpulan' => now()->addDays(8)->toDateString(),
                'nilai' => 70,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_rekap_kelas' => 3,
                'id_siswa' => 3,
                'id_mapel' => 3,
                'nama_tugas' => 'Tugas Kimia 1',
                'tanggal_pengumpulan' => now()->addDays(4)->toDateString(),
                'nilai' => 90,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rekap_kelas' => 3,
                'id_siswa' => 3,
                'id_mapel' => 3,
                'nama_tugas' => 'Tugas Kimia 2',
                'tanggal_pengumpulan' => now()->addDays(7)->toDateString(),
                'nilai' => 85,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_rekap_kelas' => 4,
                'id_siswa' => 4,
                'id_mapel' => 4,
                'nama_tugas' => 'Tugas Biologi 1',
                'tanggal_pengumpulan' => now()->addDays(6)->toDateString(),
                'nilai' => 75,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rekap_kelas' => 4,
                'id_siswa' => 4,
                'id_mapel' => 4,
                'nama_tugas' => 'Tugas Biologi 2',
                'tanggal_pengumpulan' => now()->addDays(9)->toDateString(),
                'nilai' => 88,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_rekap_kelas' => 5,
                'id_siswa' => 5,
                'id_mapel' => 5,
                'nama_tugas' => 'Tugas Sejarah 1',
                'tanggal_pengumpulan' => now()->addDays(2)->toDateString(),
                'nilai' => 0,
                'status' => 'Belum Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rekap_kelas' => 5,
                'id_siswa' => 5,
                'id_mapel' => 5,
                'nama_tugas' => 'Tugas Sejarah 2',
                'tanggal_pengumpulan' => now()->addDays(7)->toDateString(),
                'nilai' => 78,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_rekap_kelas' => 1,
                'id_siswa' => 8,
                'id_mapel' => 1,
                'nama_tugas' => 'Tugas Matematika 1',
                'tanggal_pengumpulan' => now()->addDays(3)->toDateString(),
                'nilai' => 0,
                'status' => 'Belum Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rekap_kelas' => 1,
                'id_siswa' => 8,
                'id_mapel' => 1,
                'nama_tugas' => 'Tugas Matematika 2',
                'tanggal_pengumpulan' => now()->addDays(6)->toDateString(),
                'nilai' => 95,
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
