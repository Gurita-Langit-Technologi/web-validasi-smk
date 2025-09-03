<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use App\Models\PerwalianKelas;
use App\Models\RekapKelas;
use App\Models\RekapPengumpulan;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WaliController extends Controller
{

    public function create()
    {
        return view('auth.register-wali');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_wali' => 'required|exists:wali_kelas,kode_wali',
            'email' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $wali = WaliKelas::where('kode_wali', $request->kode_wali)->first();

        if (!$wali) {
            return back()->withErrors(['kode wali' => 'kode wali tidak ditemukan']);
        }

        $wali->password = Hash::make($request->password);
        $wali->save();

        return redirect()->route('login.wali.form')->with('success', 'Password berhasil dibuat, silakan login.');
    }

    public function dashboard()
    {

        $waliKelas = Auth::guard('wali')->user();

        $waliKelas = WaliKelas::with('kelas')->find($waliKelas->id_wali_kelas);

        if (!$waliKelas || !$waliKelas->kelas) {
            return view('pages.dashboard.dashboard-wali', [
                'isWaliKelas' => true,
                'error' => 'Anda belum ditugaskan sebagai wali kelas'
            ]);
        }

        // Ambil perwalian dari data wali langsung
        $perwalian = $waliKelas;

        $mapelList = Mapel::whereHas('rekapKelas', function ($query) use ($perwalian) {
            $query->where('id_kelas', $perwalian->id_kelas);
        })->get();

        $siswaList = Siswa::whereHas('rekapPengumpulan', function ($query) use ($perwalian) {
            $query->whereHas('rekapKelas', function ($q) use ($perwalian) {
                $q->where('id_kelas', $perwalian->id_kelas);
            });
        })->get();

        $tugasData = [];
        $tugasList = RekapPengumpulan::whereHas('rekapKelas', function ($q) use ($perwalian) {
            $q->where('id_kelas', $perwalian->id_kelas);
        })
            ->with('mapel')
            ->select('nama_tugas', 'id_mapel')
            ->distinct()
            ->orderBy('id_mapel')
            ->orderBy('nama_tugas')
            ->get()
            ->groupBy('id_mapel')
            ->flatMap(function ($group) {
                return $group->pluck('nama_tugas');
            });


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
                'nama_diklat' => $mapel->nama_diklat,
                'siswa' => []
            ];

            foreach ($siswaList as $siswa) {
                $completed = RekapPengumpulan::whereHas('rekapKelas', function ($q) use ($mapel, $perwalian) {
                    $q->where('id_mapel', $mapel->id_mapel)
                        ->where('id_kelas', $perwalian->id_kelas);
                })
                    ->where('id_siswa', $siswa->id_siswa)
                    ->where('status', 'Selesai')
                    ->count();

                $total = RekapKelas::where('id_mapel', $mapel->id_mapel)
                    ->where('id_kelas', $perwalian->id_kelas)
                    ->value('total_tugas');

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
