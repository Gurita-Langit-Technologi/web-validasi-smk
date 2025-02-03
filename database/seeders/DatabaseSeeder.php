<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        ]);

        DB::table('guru')->insert([
            ['nip' => '1234567890', 'nama_guru' => 'Budi Santoso', 'created_at' => now(), 'updated_at' => now()],
            ['nip' => '0987654321', 'nama_guru' => 'Ani Setiawati', 'created_at' => now(), 'updated_at' => now()],
            ['nip' => '1122334455', 'nama_guru' => 'Dewi Lestari', 'created_at' => now(), 'updated_at' => now()],
            ['nip' => '5566778899', 'nama_guru' => 'Samsul Arifin', 'created_at' => now(), 'updated_at' => now()],
            ['nip' => '6677889900', 'nama_guru' => 'Rahmat Hidayat', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk tabel kelas
        DB::table('kelas')->insert([
            ['nama_kelas' => 'X IPA 1', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'X IPA 2', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'X IPS 1', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'X IPS 2', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'XI IPA 1', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk tabel siswa
        DB::table('siswa')->insert([
            ['nisn' => '0011223344', 'nama_siswa' => 'Ahmad Ridwan', 'kelas' => 'X IPA 1', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['nisn' => '0055667788', 'nama_siswa' => 'Siti Rahma', 'kelas' => 'X IPA 2', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
            ['nisn' => '0099887766', 'nama_siswa' => 'Joko Prasetyo', 'kelas' => 'X IPS 1', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['nisn' => '0022334455', 'nama_siswa' => 'Nina Febrianti', 'kelas' => 'X IPS 2', 'jurusan' => 'IPS', 'created_at' => now(), 'updated_at' => now()],
            ['nisn' => '0044556677', 'nama_siswa' => 'Bambang Sugiarto', 'kelas' => 'XI IPA 1', 'jurusan' => 'IPA', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk tabel mapel
        DB::table('mapel')->insert([
            ['nama_mapel' => 'Matematika', 'tema_tugas' => 'Aljabar', 'total_tugas' => 5, 'jumlah_selesai' => 3, 'jumlah_tanggungan' => 2, 'deskripsi' => 'Belajar dasar aljabar', 'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Fisika', 'tema_tugas' => 'Gerak Lurus', 'total_tugas' => 4, 'jumlah_selesai' => 2, 'jumlah_tanggungan' => 2, 'deskripsi' => 'Hukum Newton', 'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Kimia', 'tema_tugas' => 'Reaksi Kimia', 'total_tugas' => 6, 'jumlah_selesai' => 4, 'jumlah_tanggungan' => 2, 'deskripsi' => 'Analisis senyawa', 'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Biologi', 'tema_tugas' => 'Genetika', 'total_tugas' => 3, 'jumlah_selesai' => 2, 'jumlah_tanggungan' => 1, 'deskripsi' => 'Struktur DNA', 'created_at' => now(), 'updated_at' => now()],
            ['nama_mapel' => 'Sejarah', 'tema_tugas' => 'Peradaban Dunia', 'total_tugas' => 5, 'jumlah_selesai' => 3, 'jumlah_tanggungan' => 2, 'deskripsi' => 'Perkembangan sejarah dunia', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk tabel tugas
        $mapels = DB::table('mapel')->get();

        foreach ($mapels as $mapel) {
            for ($i = 1; $i <= $mapel->total_tugas; $i++) {
                DB::table('tugas')->insert([
                    'id_siswa' => rand(1, 5),  // Assign a random siswa
                    'id_mapel' => $mapel->id_mapel,
                    'nama_tugas' => $mapel->nama_mapel . ' Tugas ' . $i,
                    'Tanggal_pengumpulan' => now()->addDays(rand(7, 14))->toDateString(),  // Random due date between 7 and 14 days
                    'keterangan' => 'Tugas nomor ' . $i . ' untuk ' . $mapel->nama_mapel,
                    'status' => $i % 2 == 0 ? 'Selesai' : 'Belum Selesai',  // Random status
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('rekap_kelas')->insert([
            ['id_kelas' => 1, 'id_mapel' => 1, 'id_guru' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 2, 'id_mapel' => 2, 'id_guru' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 3, 'id_mapel' => 3, 'id_guru' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 4, 'id_mapel' => 4, 'id_guru' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id_kelas' => 5, 'id_mapel' => 5, 'id_guru' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
