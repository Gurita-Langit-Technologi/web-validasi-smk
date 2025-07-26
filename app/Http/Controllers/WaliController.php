<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use App\Models\PerwalianKelas;
use App\Models\RekapKelas;
use App\Models\RekapPengumpulan;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class WaliController extends Controller
{
    public function dashboard()
    {

        $waliKelas = Auth::guard('wali')->user();

        $perwalian = PerwalianKelas::with('kelas')->where('id_wali_kelas', $waliKelas->id_wali_kelas)->first();

        if (!$perwalian) {
            return view('pages.app.dashboard', [
                'isWaliKelas' => true,
                'error' => 'Anda belum ditugaskan sebagai wali kelas'
            ]);
        }

        // Dapatkan semua mapel di kelas ini
        $mapelList = Mapel::whereHas('rekapKelas', function ($query) use ($perwalian) {
            $query->where('id_kelas', $perwalian->id_kelas);
        })->get();

        // Dapatkan semua siswa di kelas ini
        $siswaList = Siswa::whereHas('rekapPengumpulan', function ($query) use ($perwalian) {
            $query->whereHas('rekapKelas', function ($q) use ($perwalian) {
                $q->where('id_kelas', $perwalian->id_kelas);
            });
        })->get();

        // Siapkan data untuk tabel pertama (detail tugas)
        $tugasData = [];
        $tugasList = RekapPengumpulan::whereHas('rekapKelas', function ($q) use ($perwalian) {
            $q->where('id_kelas', $perwalian->id_kelas);
        })
            ->select('nama_tugas')
            ->distinct()
            ->orderBy('nama_tugas')
            ->take(5)
            ->pluck('nama_tugas');

        foreach ($siswaList as $siswa) {
            $tugasData[$siswa->id_siswa] = [
                'nama' => $siswa->nama_siswa,
                'tugas' => []
            ];

            foreach ($tugasList as $tugas) {
                $status = RekapPengumpulan::where('id_siswa', $siswa->id_siswa)
                    ->where('nama_tugas', $tugas)
                    ->value('status') ?? 'Belum Selesai';

                $tugasData[$siswa->id_siswa]['tugas'][$tugas] = [
                    'status' => $status
                ];
            }
        }

        $mapelProgress = [];
        foreach ($mapelList as $mapel) {
            $mapelProgress[$mapel->id_mapel] = [
                'nama_mapel' => $mapel->nama_mapel,
                'siswa' => []
            ];

            foreach ($siswaList as $siswa) {
                // Hitung tugas yang selesai untuk mapel ini
                $completed = RekapPengumpulan::whereHas('rekapKelas', function ($q) use ($mapel, $perwalian) {
                    $q->where('id_mapel', $mapel->id_mapel)
                        ->where('id_kelas', $perwalian->id_kelas);
                })
                    ->where('id_siswa', $siswa->id_siswa)
                    ->where('status', 'Selesai')
                    ->count();

                // Hitung total tugas untuk mapel ini
                $total = RekapKelas::where('id_mapel', $mapel->id_mapel)
                    ->where('id_kelas', $perwalian->id_kelas)
                    ->value('total_tugas');

                // Debugging - tambahkan ini untuk memeriksa data
                // Log::info("Mapel: {$mapel->nama_mapel}, Siswa: {$siswa->nama_siswa}, Completed: {$completed}, Total: {$total}");

                $mapelProgress[$mapel->id_mapel]['siswa'][$siswa->id_siswa] = ($total > 0) && ($completed == $total);
            }
        }


        return view('pages.dashboard.dashboard-wali', [
            'isWaliKelas' => true,
            'waliKelas' => $waliKelas,
            'kelas' => $perwalian->kelas,
            'tugasData' => $tugasData,
            'tugasList' => $tugasList,
            'mapelProgress' => $mapelProgress,
            'siswaList' => $siswaList,
            'mapelList' => $mapelList
        ]);

        return redirect()->route('login.wali.form');
    }
}
