<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\PerwalianKelas;
use App\Models\RekapKelas;
use App\Models\RekapPengumpulan;
use App\Models\Siswa;
use App\Models\UserGuru;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_guru' => 'required|exists:guru,kode_guru',
            'email' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $guru = Guru::where('kode_guru', $request->kode_guru)->first();
        // $user_guru = UserGuru::where('guru_id', $guru->id_guru)->first();

        if (!$guru) {
            return back()->withErrors(['kode guru' => 'kode guru tidak ditemukan']);
        }

        $user_guru = new UserGuru;
        $user_guru->guru_id = $guru->id_guru;
        $user_guru->email = $request->email;
        $user_guru->password = Hash::make($request->password);
        $user_guru->save();

        return redirect()->route('login.guru.form')->with('success', 'Password berhasil dibuat, silakan login.');
    }

    public function dashboard()
    {
        if (Auth::guard('wali')->check()) {
            $waliKelas = Auth::guard('wali')->user();

            // Dapatkan kelas yang diampu wali kelas ini
            $perwalian = PerwalianKelas::with('kelas')->where('id_wali_kelas', $waliKelas->id_wali_kelas)->first();

            if (!$perwalian) {
                return view('pages.app.dashboard', [
                    'isWaliKelas' => true,
                    'error' => 'Anda belum ditugaskan sebagai wali kelas'
                ]);
            }

            // Dapatkan semua mapel di kelas ini dengan tugas-tugasnya
            $mapelList = Mapel::whereHas('rekapKelas', function ($query) use ($perwalian) {
                $query->where('id_kelas', $perwalian->id_kelas);
            })->with(['rekapKelas' => function ($query) use ($perwalian) {
                $query->where('id_kelas', $perwalian->id_kelas)
                    ->with(['tugas' => function ($q) {
                        $q->orderBy('tanggal_pengumpulan', 'asc');
                    }]);
            }])->get();

            // Dapatkan semua siswa di kelas ini
            $siswaList = Siswa::whereHas('rekapPengumpulan', function ($query) use ($perwalian) {
                $query->whereHas('rekapKelas', function ($q) use ($perwalian) {
                    $q->where('id_kelas', $perwalian->id_kelas);
                });
            })->get();

            // Siapkan data untuk tabel detail tugas
            $tugasData = [];
            $tugasColumns = [];

            foreach ($mapelList as $mapel) {
                foreach ($mapel->rekapKelas as $rekapKelas) {
                    foreach ($rekapKelas->tugas as $tugas) {
                        // Simpan nama tugas unik untuk kolom tabel
                        if (!in_array($tugas->nama_tugas, $tugasColumns)) {
                            $tugasColumns[] = $tugas->nama_tugas;
                        }
                    }
                }
            }

            // Batasi hanya 5 tugas teratas
            $tugasColumns = array_slice($tugasColumns, 0, 5);

            foreach ($siswaList as $siswa) {
                $tugasData[$siswa->id_siswa] = [
                    'nama' => $siswa->nama_siswa,
                    'tugas' => []
                ];

                foreach ($tugasColumns as $namaTugas) {
                    $tugas = RekapPengumpulan::where('id_siswa', $siswa->id_siswa)
                        ->where('nama_tugas', $namaTugas)
                        ->orderBy('tanggal_pengumpulan', 'asc')
                        ->first();

                    $tugasData[$siswa->id_siswa]['tugas'][$namaTugas] = [
                        'status' => $tugas ? $tugas->status : 'Belum Selesai',
                        'nilai' => $tugas ? $tugas->nilai : null
                    ];
                }
            }

            // Siapkan data untuk tabel rekap mapel (sama seperti sebelumnya)
            $mapelProgress = [];
            foreach ($mapelList as $mapel) {
                $mapelProgress[$mapel->id_mapel] = [
                    'nama_mapel' => $mapel->nama_mapel,
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

                    $mapelProgress[$mapel->id_mapel]['siswa'][$siswa->id_siswa] = [
                        'completed' => $completed,
                        'total' => $total,
                        'completed_all' => $completed == $total
                    ];
                }
            }

            return view('pages.app.dashboard', [
                'isWaliKelas' => true,
                'waliKelas' => $waliKelas,
                'kelas' => $perwalian->kelas,
                'tugasData' => $tugasData,
                'tugasColumns' => $tugasColumns,
                'mapelProgress' => $mapelProgress,
                'siswaList' => $siswaList,
                'mapelList' => $mapelList
            ]);
        }

        if (Auth::guard('guru')->check()) {
            $guru = Auth::guard('guru')->user();
            return view('pages.app.dashboard', [
                'isWaliKelas' => false,
                'guru' => $guru
            ]);
        }

        return redirect()->route('login.guru.form');
    }
}
