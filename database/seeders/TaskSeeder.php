<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        //alternatif seeder 1
        DB::table('tasks')->insert([
            [
                'uraian_kegiatan' => 'Rapat Persiapan Guru',
                'start' => '2025-08-10 08:00:00',
                'end' => '2025-08-10 12:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uraian_kegiatan' => 'Pendaftaran Siswa Baru',
                'start' => '2025-08-01 00:00:00',
                'end' => '2025-08-07 23:59:59',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uraian_kegiatan' => 'ANBK Kelas X',
                'start' => '2025-08-15 08:00:00',
                'end' => '2025-08-16 12:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);



        /*
        $startDate = \Carbon\Carbon::create(2025, 8, 1, 8); // mulai dari 1 Agustus
        $kegiatanList = [
            'Rapat Persiapan Guru',
            'Pengenalan Kurikulum',
            'Evaluasi Siswa Semester Lalu',
            'Workshop Pengajaran',
            'Koordinasi Wali Kelas',
            'Rapat Orang Tua Siswa',
            'Pelatihan IT Guru',
            'Persiapan Ujian Tengah Semester',
            'Rapat Manajemen Sekolah',
            'Kegiatan OSIS dan Ekstrakurikuler'
        ];

        $data = [];

        foreach ($kegiatanList as $index => $kegiatan) {
            $start = $startDate->copy()->addDays($index);
            $end = $start->copy()->addHours(4);

            $data[] = [
                'uraian_kegiatan' => $kegiatan,


                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tasks')->insert($data);
        */
    }
}
