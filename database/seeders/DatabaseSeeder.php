<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        // DB::table('guru')->insert([
        //     ['kode_guru' => '1234567890', 'nama_guru' => 'Budi Santoso',  'created_at' => now(), 'updated_at' => now()],
        //     ['kode_guru' => '0987654321', 'nama_guru' => 'Ani Setiawati',  'created_at' => now(), 'updated_at' => now()],
        //     ['kode_guru' => '1122334455', 'nama_guru' => 'Dewi Lestari',  'created_at' => now(), 'updated_at' => now()],
        //     ['kode_guru' => '5566778899', 'nama_guru' => 'Samsul Arifin',  'created_at' => now(), 'updated_at' => now()],
        //     ['kode_guru' => '6677889900', 'nama_guru' => 'Rahmat Hidayat',  'created_at' => now(), 'updated_at' => now()],
        // ]);

        // DB::table('user_guru')->insert([
        //     ['guru_id' => 1, 'email' => 'budisantoso@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()],
        //     ['guru_id' => 2, 'email' => 'soraxora@gmail.com', 'password' => Hash::make('12345678'), 'created_at' => now(), 'updated_at' => now()]
        // ]);


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

        DB::table('rekap_kelas')->insert([
            ['id_kelas' => 1, 'id_mapel' => 1, 'id_guru' => 1,  'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 2, 'id_mapel' => 2, 'id_guru' => 2,  'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 3, 'id_mapel' => 3, 'id_guru' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 4, 'id_mapel' => 4, 'id_guru' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 5, 'id_mapel' => 5, 'id_guru' => 5,  'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('tugas_mengajar')->insert([
            ['id_mengajar' => 1,  'kode_guru' => '1234567890', 'nama_guru' => 'Budi Santoso', 'kelas' => 'X IPA 1', 'mata_diklat' => 'Matematika', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 2,  'kode_guru' => '0987654321', 'nama_guru' => 'Ani Setiawati', 'kelas' => 'X IPA 2', 'mata_diklat' => 'Fisika', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 3,  'kode_guru' => '1122334455', 'nama_guru' => 'Dewi Lestari', 'kelas' => 'X IPS 1', 'mata_diklat' => 'Kimia', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 4,  'kode_guru' => '5566778899', 'nama_guru' => 'Samsul Arifin', 'kelas' => 'X IPS 2', 'mata_diklat' => 'Biologi', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['id_mengajar' => 5,  'kode_guru' => '6677889900', 'nama_guru' => 'Rahmat Hidayat', 'kelas' => 'XI IPA 1', 'mata_diklat' => 'Sejarah', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
