<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678')
        ]);

        DB::table('guru')->insert([
            ['kode_guru' => '1234567890', 'nama_guru' => 'Budi Santoso', 'role' => 'guru', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '1234567890', 'nama_guru' => 'Budi Santoso', 'role' => 'wali kelas', 'created_at' => now(), 'updated_at' => now()],

            ['kode_guru' => '0987654321', 'nama_guru' => 'Ani Setiawati', 'role' => 'guru', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '0987654321', 'nama_guru' => 'Ani Setiawati', 'role' => 'wali kelas', 'created_at' => now(), 'updated_at' => now()],

            ['kode_guru' => '1122334455', 'nama_guru' => 'Dewi Lestari', 'role' => 'guru', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '1122334455', 'nama_guru' => 'Dewi Lestari', 'role' => 'wali kelas', 'created_at' => now(), 'updated_at' => now()],

            ['kode_guru' => '5566778899', 'nama_guru' => 'Samsul Arifin', 'role' => 'guru', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '5566778899', 'nama_guru' => 'Samsul Arifin', 'role' => 'wali kelas', 'created_at' => now(), 'updated_at' => now()],

            ['kode_guru' => '6677889900', 'nama_guru' => 'Rahmat Hidayat', 'role' => 'guru', 'created_at' => now(), 'updated_at' => now()],
            ['kode_guru' => '6677889900', 'nama_guru' => 'Rahmat Hidayat', 'role' => 'wali kelas', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('user_guru')->insert([
            ['guru_id' => 1, 'email' => 'budisantoso@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['guru_id' => 2, 'email' => 'budisantoso1@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['guru_id' => 3, 'email' => 'soraxora@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()]
        ]);


        // Seeder untuk tabel kelas
        DB::table('kelas')->insert([
            ['kode_kelas' => 'XIPA1', 'nama_kelas' => 'X IPA 1', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'XIPA2', 'nama_kelas' => 'X IPA 2', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'XIPS1', 'nama_kelas' => 'X IPS 1', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'XIPS2', 'nama_kelas' => 'X IPS 2', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'XIIPA1', 'nama_kelas' => 'XI IPA 1', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'XIIPA2', 'nama_kelas' => 'XI IPA 2', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'XIIPS1', 'nama_kelas' => 'XI IPS 1', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk tabel siswa
        DB::table('siswa')->insert([
            [
                'nisn' => '123456789012',
                'nama_siswa' => 'Andi Saputra',
                'nama_kelas' => 'X IPA 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '234567890123',
                'nama_siswa' => 'Budi Santoso',
                'nama_kelas' => 'X IPA 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '345678901234',
                'nama_siswa' => 'Citra Ayu',
                'nama_kelas' => 'X IPS 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '456789012345',
                'nama_siswa' => 'Dedi Kusuma',
                'nama_kelas' => 'X IPS 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '567890123456',
                'nama_siswa' => 'Eka Putri',
                'nama_kelas' => 'XI IPA 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '678901234567',
                'nama_siswa' => 'Fajar Hidayat',
                'nama_kelas' => 'XI IPA 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '789012345678',
                'nama_siswa' => 'Gina Larasati',
                'nama_kelas' => 'XI IPS 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '890123456789',
                'nama_siswa' => 'Hadi Pratama',
                'nama_kelas' => 'X IPA 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '901234567890',
                'nama_siswa' => 'Indah Safitri',
                'nama_kelas' => 'X IPA 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '012345678901',
                'nama_siswa' => 'Joko Wahyu',
                'nama_kelas' => 'X IPS 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seeder untuk tabel mapel
        DB::table('mapel')->insert([
            ['nama_mapel' => 'Matematika', 'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Fisika', 'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Kimia',   'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Biologi',   'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Sejarah',    'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk tabel tugas
        $mapels = DB::table('mapel')->get();

        // foreach ($mapels as $mapel) {
        //     for ($i = 1; $i <= $mapel->total_tugas; $i++) {
        //         DB::table('tugas')->insert([
        //             'id_siswa' => rand(1, 5),  // Assign a random siswa
        //             'id_mapel' => $mapel->id_mapel,
        //             'nama_tugas' => $mapel->nama_mapel . ' Tugas ' . $i,
        //             'Tanggal_pengumpulan' => now()->addDays(rand(7, 14))->toDateString(),  // Random due date between 7 and 14 days
        //             'keterangan' => 'Tugas nomor ' . $i . ' untuk ' . $mapel->nama_mapel,
        //             'status' => $i % 2 == 0 ? 'Selesai' : 'Belum Selesai',  // Random status
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }

        DB::table('tugas_mengajar')->insert([
            ['id_mengajar' => 1,  'kode_guru' => '1234567890', 'nama_guru' => 'Budi Santoso', 'kelas' => 'X IPA 1', 'mata_diklat' => 'Matematika', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 2,  'kode_guru' => '0987654321', 'nama_guru' => 'Ani Setiawati', 'kelas' => 'X IPA 2', 'mata_diklat' => 'Fisika', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 3,  'kode_guru' => '1122334455', 'nama_guru' => 'Dewi Lestari', 'kelas' => 'X IPS 1', 'mata_diklat' => 'Kimia', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 4,  'kode_guru' => '5566778899', 'nama_guru' => 'Samsul Arifin', 'kelas' => 'X IPS 2', 'mata_diklat' => 'Biologi', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 5,  'kode_guru' => '6677889900', 'nama_guru' => 'Rahmat Hidayat', 'kelas' => 'XI IPA 1', 'mata_diklat' => 'Sejarah', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('wali_kelas')->insert([
            ['kode_wali' => '1234567890', 'nama_wali' => 'Budi Santoso',  'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['kode_wali' => '0987654321', 'nama_wali' => 'Ani Setiawati', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['kode_wali' => '1122334455', 'nama_wali' => 'Dewi Lestari', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['kode_wali' => '5566778899', 'nama_wali' => 'Samsul Arifin', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
            ['kode_wali' => '6677889900', 'nama_wali' => 'Rahmat Hidayat', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('perwalian_kelas')->insert([
            ['id_wali_kelas' => 1, 'id_kelas' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 2, 'id_kelas' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 3, 'id_kelas' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 4, 'id_kelas' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id_wali_kelas' => 5, 'id_kelas' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $waliKelasIds = DB::table('wali_kelas')->pluck('id_wali_kelas')->toArray();

        DB::table('rekap_kelas')->insert([
            [
                'id_kelas' => 1,
                'id_mapel' => 1,
                'id_guru' => 1,
                'id_wali_kelas' => $waliKelasIds[0] ?? 1,
                'total_tugas' => 0,
                'jumlah_selesai' => 0,
                'jumlah_tanggungan' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 2,
                'id_mapel' => 2,
                'id_guru' => 2,
                'id_wali_kelas' => $waliKelasIds[1] ?? 2,
                'total_tugas' => 0,
                'jumlah_selesai' => 0,
                'jumlah_tanggungan' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 3,
                'id_mapel' => 3,
                'id_guru' => 3,
                'id_wali_kelas' => $waliKelasIds[2] ?? 3,
                'total_tugas' => 0,
                'jumlah_selesai' => 0,
                'jumlah_tanggungan' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 4,
                'id_mapel' => 4,
                'id_guru' => 4,
                'id_wali_kelas' => $waliKelasIds[3] ?? 4,
                'total_tugas' => 0,
                'jumlah_selesai' => 0,
                'jumlah_tanggungan' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_kelas' => 5,
                'id_mapel' => 5,
                'id_guru' => 5,
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
        ]);
    }
}
