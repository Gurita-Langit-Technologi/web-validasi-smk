<?php

namespace App\Http\Controllers;

use App\Models\WaliKelas;
use App\Models\Mapel;
use App\Models\RekapKelas;
use App\Models\Siswa;
use App\Models\RekapPengumpulan;
use App\Models\TugasMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekapTugasController extends Controller
{

    public function index()
    {
        $tugasMengajar = TugasMengajar::with([
            'kelas',
            'mapel',
            'guru'
        ])->get();

        $rekapTugas = collect();
        $guruIdLogin = Auth::guard('guru')->user()->id_guru;


        $waliKelasMap = WaliKelas::with('kelas')->get()->keyBy('id_kelas');

        foreach ($tugasMengajar as $mengajar) {
            $waliKelas = $waliKelasMap->get($mengajar->id_kelas);
            // tambahan
            // Jika tidak ada wali kelas untuk id_kelas ini, lewati iterasi ini
            // atau kirim pesan error
            if (!$waliKelas) {
                continue; // Melewati kelas yang tidak memiliki wali kelas
            }

            $rekap = RekapKelas::firstOrCreate(
                [
                    'id_kelas' => $mengajar->id_kelas,
                    'id_mapel' => $mengajar->id_mapel,
                    'id_guru' => $mengajar->id_guru,
                ],
                [
                    //'id_wali_kelas' => $waliKelas ? $waliKelas->id_wali_kelas : null, // katanya gemini disini masalahnya
                    'id_wali_kelas' => $waliKelas->id_wali_kelas,
                    'total_tugas' => 0,
                    'jumlah_selesai' => 0,
                    'jumlah_tanggungan' => 0,
                ]
            );


            $rekap->load(['kelas', 'mapel', 'guru', 'tugas', 'waliKelas']);

            $rekap->total_tugas = $rekap->tugas->unique('nama_tugas')->count();

            if ($rekap->total_tugas > 0) {
                // 1. Dapatkan semua id_siswa di kelas ini yang memiliki rekapan
                $semuaSiswaDiKelas = RekapPengumpulan::where('id_rekap_kelas', $rekap->id_rekap_kelas)
                    ->distinct()
                    ->pluck('id_siswa');

                $jumlahSiswaSelesaiSemua = 0;
                $jumlahSiswaBelumSelesai = 0;

                foreach ($semuaSiswaDiKelas as $idSiswa) {

                    $tugasSelesaiPerSiswa = RekapPengumpulan::where('id_rekap_kelas', $rekap->id_rekap_kelas)
                        ->where('id_siswa', $idSiswa)
                        ->where('status', 'Selesai')
                        ->count();


                    if ($tugasSelesaiPerSiswa >= $rekap->total_tugas) {

                        $jumlahSiswaSelesaiSemua++;
                    } else {

                        $jumlahSiswaBelumSelesai++;
                    }
                }

                $rekap->jumlah_selesai = $jumlahSiswaSelesaiSemua;

                $rekap->jumlah_tanggungan = $jumlahSiswaBelumSelesai;
            } else {
                $rekap->jumlah_selesai = 0;
                $rekap->jumlah_tanggungan = 0;
            }
            $rekap->save();

            if ($mengajar->id_guru == $guruIdLogin) {
                $rekapTugas->push($rekap);
            }
        }

        return view('pages.app.page-tugas', compact('rekapTugas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_tugas' => 'required|integer|min:0',
            'jumlah_selesai' => 'required|integer|min:0',
        ]);

        $rekap = RekapKelas::findOrFail($id);
        $rekap->update([
            'total_tugas' => $request->total_tugas,
            'jumlah_selesai' => $request->jumlah_selesai,
            'jumlah_tanggungan' => $request->total_tugas - $request->jumlah_selesai,
        ]);

        return redirect()->route('page-tugas')->with('success', 'Data berhasil diperbarui!');
    }

    public function showDetailTugas($id_mapel, $id_kelas)
    {
        $guruIdLogin = Auth::guard('guru')->user()->id_guru;

        // Otorisasi: pastikan guru yang login memang mengajar mapel & kelas ini
        $isTeaching = TugasMengajar::where('id_guru', $guruIdLogin)
            ->where('id_mapel', $id_mapel)
            ->where('id_kelas', $id_kelas)
            ->exists();

        if (!$isTeaching) {
            return redirect()->route('page-kelas')->with('error', 'Anda tidak memiliki hak akses mengajar untuk kelas dan mata pelajaran ini.');
        }

        $mapel = Mapel::findOrFail($id_mapel);
        $waliKelas = WaliKelas::where('id_kelas', $id_kelas)->first();

        // Otomatis buat atau ambil RekapKelas agar tidak pernah error 404 saat dibuka dari menu Kelas
        $rekapKelas = RekapKelas::firstOrCreate(
            [
                'id_kelas' => $id_kelas,
                'id_mapel' => $id_mapel,
                'id_guru' => $guruIdLogin,
            ],
            [
                'id_wali_kelas' => $waliKelas ? $waliKelas->id_wali_kelas : null,
                'total_tugas' => 0,
                'jumlah_selesai' => 0,
                'jumlah_tanggungan' => 0,
            ]
        );

        $rekapKelas->load('kelas');

        $siswa = Siswa::where('id_kelas', $rekapKelas->id_kelas)->orderBy('nama_siswa')->get();

        // Ambil daftar nama tugas unik untuk header tabel
        $tugas = RekapPengumpulan::where('id_mapel', $id_mapel)
            ->where('id_rekap_kelas', $rekapKelas->id_rekap_kelas)
            ->select('id_tugas', 'nama_tugas')
            ->get()
            ->unique('nama_tugas');

        // Ambil seluruh data pengumpulan untuk kelas dan mapel ini dalam 1 query (eliminasi N+1 query)
        $allPengumpulan = RekapPengumpulan::where('id_mapel', $id_mapel)
            ->where('id_rekap_kelas', $rekapKelas->id_rekap_kelas)
            ->get();

        $siswaPengumpulanGrouped = $allPengumpulan->groupBy('id_siswa');

        $siswaTugas = [];
        $siswaStatus = [];
        $pengumpulanMap = [];

        foreach ($siswa as $student) {
            $studentTasks = $siswaPengumpulanGrouped->get($student->id_siswa, collect());
            $siswaTugas[$student->id_siswa] = $studentTasks;

            foreach ($studentTasks as $stTask) {
                $pengumpulanMap[$student->id_siswa . '_' . $stTask->id_tugas] = $stTask;
            }

            $completedTasks = $studentTasks->where('status', 'Selesai')->count();
            $totalTasks = $studentTasks->count();

            if ($totalTasks == 0) {
                $status = 'danger';
            } elseif ($completedTasks >= $totalTasks) {
                $status = 'success';
            } elseif ($completedTasks > 0) {
                $status = 'warning';
            } else {
                $status = 'danger';
            }

            $siswaStatus[$student->id_siswa] = [
                'status' => $status,
                'completed' => $completedTasks,
                'total' => $totalTasks,
            ];
        }

        return view('pages.app.ceklis-tugas', compact('mapel', 'tugas', 'siswa', 'siswaTugas', 'siswaStatus', 'rekapKelas', 'pengumpulanMap'));
    }

    public function updateStatusTugas(Request $request)
    {
        $guruIdLogin = Auth::guard('guru')->user()->id_guru;
        $id_rekap_kelas = $request->id_rekap_kelas;
        $id_mapel = $request->id_mapel;

        $rekapKelas = RekapKelas::findOrFail($id_rekap_kelas);
        if ($rekapKelas->id_guru != $guruIdLogin) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah data kelas ini.');
        }

        // 1. Simpan tugas baru jika ada input nama_tugas_baru
        $namaTugasBaru = trim($request->input('nama_tugas_baru', ''));
        if (!empty($namaTugasBaru)) {
            $siswaList = Siswa::where('id_kelas', $rekapKelas->id_kelas)->get();

            foreach ($siswaList as $student) {
                $newStatus = $request->input("tugas.{$student->id_siswa}.new", 'Belum Selesai');
                $newTgl = $request->input("tanggal_pengumpulan.{$student->id_siswa}.new");
                $newKet = $request->input("keterangan.{$student->id_siswa}.new");
                $newNilai = $request->input("nilai.{$student->id_siswa}.new", 0);

                RekapPengumpulan::create([
                    'nama_tugas' => $namaTugasBaru,
                    'id_mapel' => $id_mapel,
                    'id_rekap_kelas' => $id_rekap_kelas,
                    'id_siswa' => $student->id_siswa,
                    'status' => ($newStatus === 'Selesai') ? 'Selesai' : 'Belum Selesai',
                    'tanggal_pengumpulan' => ($newStatus === 'Selesai') ? ($newTgl ?: now()->toDateString()) : null,
                    'keterangan' => $newKet,
                    'nilai' => (is_numeric($newNilai) && $newNilai >= 0 && $newNilai <= 100) ? (int)$newNilai : 0,
                ]);
            }
        }

        // 2. Update tugas-tugas yang sudah ada
        if ($request->has('tugas') && is_array($request->tugas)) {
            foreach ($request->tugas as $siswaId => $tugasItems) {
                if (!is_array($tugasItems)) continue;
                foreach ($tugasItems as $tugasId => $status) {
                    if ($tugasId === 'new') continue; // Sudah ditangani di atas

                    $rekap = RekapPengumpulan::where('id_tugas', $tugasId)
                        ->where('id_siswa', $siswaId)
                        ->where('id_rekap_kelas', $id_rekap_kelas)
                        ->first();

                    if ($rekap) {
                        $rekap->status = ($status === 'Selesai') ? 'Selesai' : 'Belum Selesai';
                        $rekap->tanggal_pengumpulan = ($status === 'Selesai')
                            ? ($request->tanggal_pengumpulan[$siswaId][$tugasId] ?? $rekap->tanggal_pengumpulan ?? now()->toDateString())
                            : null;
                        $rekap->keterangan = $request->keterangan[$siswaId][$tugasId] ?? null;
                        $rawNilai = $request->nilai[$siswaId][$tugasId] ?? null;
                        $rekap->nilai = (is_numeric($rawNilai) && $rawNilai >= 0 && $rawNilai <= 100) ? (int)$rawNilai : 0;
                        $rekap->save();
                    }
                }
            }
        }

        // 3. Kalkulasi ulang total tugas, siswa selesai, dan tanggungan secara konsisten
        $totalTugasKelas = RekapPengumpulan::where('id_rekap_kelas', $id_rekap_kelas)
            ->distinct('nama_tugas')
            ->count('nama_tugas');

        $siswaDiKelas = Siswa::where('id_kelas', $rekapKelas->id_kelas)->pluck('id_siswa');
        $jumlahSiswaSelesaiSemua = 0;
        $jumlahSiswaBelumSelesai = 0;

        if ($totalTugasKelas > 0) {
            foreach ($siswaDiKelas as $idSiswa) {
                $tugasSelesaiPerSiswa = RekapPengumpulan::where('id_rekap_kelas', $id_rekap_kelas)
                    ->where('id_siswa', $idSiswa)
                    ->where('status', 'Selesai')
                    ->count();

                if ($tugasSelesaiPerSiswa >= $totalTugasKelas) {
                    $jumlahSiswaSelesaiSemua++;
                } else {
                    $jumlahSiswaBelumSelesai++;
                }
            }
        }

        $rekapKelas->update([
            'total_tugas' => $totalTugasKelas,
            'jumlah_selesai' => $jumlahSiswaSelesaiSemua,
            'jumlah_tanggungan' => $jumlahSiswaBelumSelesai,
        ]);

        return redirect()->back()->with('success', 'Status tugas dan nilai berhasil diperbarui.');
    }

    public function addSingleTaskPerClass(Request $request, $id_rekap)
    {
        $guruIdLogin = Auth::guard('guru')->user()->id_guru;
        $rekap = RekapKelas::findOrFail($id_rekap);

        if ($rekap->id_guru != $guruIdLogin) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk kelas ini!'
            ], 403);
        }

        $mapel = $rekap->mapel;
        $kelas = $rekap->kelas;
        $taskName = trim($request->task);

        if (!$taskName) {
            return response()->json([
                'success' => false,
                'message' => 'Nama tugas tidak boleh kosong!'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();

            foreach ($siswaList as $siswa) {
                RekapPengumpulan::create([
                    'id_rekap_kelas' => $id_rekap,
                    'id_siswa' => $siswa->id_siswa,
                    'id_mapel' => $mapel->id_mapel,
                    'nama_tugas' => $taskName,
                    'status' => 'Belum Selesai',
                ]);
            }

            $uniqueTasksCount = RekapPengumpulan::where('id_rekap_kelas', $id_rekap)
                ->distinct('nama_tugas')
                ->count('nama_tugas');

            $siswaDiKelas = Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
            $jumlahSiswaSelesaiSemua = 0;
            $jumlahSiswaBelumSelesai = 0;

            if ($uniqueTasksCount > 0) {
                foreach ($siswaDiKelas as $idSiswa) {
                    $tugasSelesaiPerSiswa = RekapPengumpulan::where('id_rekap_kelas', $id_rekap)
                        ->where('id_siswa', $idSiswa)
                        ->where('status', 'Selesai')
                        ->count();

                    if ($tugasSelesaiPerSiswa >= $uniqueTasksCount) {
                        $jumlahSiswaSelesaiSemua++;
                    } else {
                        $jumlahSiswaBelumSelesai++;
                    }
                }
            }

            $rekap->total_tugas = $uniqueTasksCount;
            $rekap->jumlah_selesai = $jumlahSiswaSelesaiSemua;
            $rekap->jumlah_tanggungan = $jumlahSiswaBelumSelesai;
            $rekap->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tugas baru berhasil ditambahkan untuk kelas ' . $kelas->nama_kelas . '!',
                'total_tugas' => $rekap->total_tugas,
                'jumlah_selesai' => $rekap->jumlah_selesai,
                'jumlah_tanggungan' => $rekap->jumlah_tanggungan,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateTasksPerClass(Request $request, $id_rekap)
    {
        $guruIdLogin = Auth::guard('guru')->user()->id_guru;
        $rekap = RekapKelas::findOrFail($id_rekap);

        if ($rekap->id_guru != $guruIdLogin) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk kelas ini!'
            ], 403);
        }

        $mapel = $rekap->mapel;
        $kelas = $rekap->kelas;
        $tasks = $request->tasks;

        DB::beginTransaction();
        try {
            foreach ($tasks as $taskData) {
                $oldName = $taskData['old_name'] ?? null;
                $newName = trim($taskData['new_name'] ?? '');

                if (empty($newName)) {
                    continue;
                }

                if ($oldName && $oldName !== $newName) {
                    RekapPengumpulan::where('id_rekap_kelas', $id_rekap)
                        ->where('nama_tugas', $oldName)
                        ->update(['nama_tugas' => $newName]);
                } else if (!$oldName) {
                    $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();

                    foreach ($siswaList as $siswa) {
                        RekapPengumpulan::create([
                            'id_rekap_kelas' => $id_rekap,
                            'id_siswa' => $siswa->id_siswa,
                            'id_mapel' => $mapel->id_mapel,
                            'nama_tugas' => $newName,
                            'status' => 'Belum Selesai',
                        ]);
                    }
                }
            }

            $uniqueTasksCount = RekapPengumpulan::where('id_rekap_kelas', $id_rekap)
                ->distinct('nama_tugas')
                ->count('nama_tugas');

            $siswaDiKelas = Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
            $jumlahSiswaSelesaiSemua = 0;
            $jumlahSiswaBelumSelesai = 0;

            if ($uniqueTasksCount > 0) {
                foreach ($siswaDiKelas as $idSiswa) {
                    $tugasSelesaiPerSiswa = RekapPengumpulan::where('id_rekap_kelas', $id_rekap)
                        ->where('id_siswa', $idSiswa)
                        ->where('status', 'Selesai')
                        ->count();

                    if ($tugasSelesaiPerSiswa >= $uniqueTasksCount) {
                        $jumlahSiswaSelesaiSemua++;
                    } else {
                        $jumlahSiswaBelumSelesai++;
                    }
                }
            }

            $rekap->total_tugas = $uniqueTasksCount;
            $rekap->jumlah_selesai = $jumlahSiswaSelesaiSemua;
            $rekap->jumlah_tanggungan = $jumlahSiswaBelumSelesai;
            $rekap->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diupdate untuk kelas ' . $kelas->nama_kelas
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateAllTasks(Request $request)
    {
        $guruIdLogin = Auth::guard('guru')->user()->id_guru;
        $allTasks = $request->tasks;
        $results = [];

        DB::beginTransaction();
        try {
            foreach ($allTasks as $classId => $tasks) {
                $rekap = RekapKelas::findOrFail($classId);

                // Pastikan hanya kelas milik guru yang login yang diproses
                if ($rekap->id_guru != $guruIdLogin) {
                    continue;
                }

                $mapel = $rekap->mapel;
                $kelas = $rekap->kelas;

                foreach ($tasks as $taskData) {
                    $oldName = $taskData['old_name'] ?? null;
                    $newName = trim($taskData['new_name'] ?? '');

                    if (empty($newName)) {
                        continue;
                    }

                    if ($oldName) {
                        RekapPengumpulan::where('id_rekap_kelas', $classId)
                            ->where('nama_tugas', $oldName)
                            ->update(['nama_tugas' => $newName]);
                    } else {
                        $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();
                        foreach ($siswaList as $siswa) {
                            RekapPengumpulan::create([
                                'id_rekap_kelas' => $classId,
                                'id_siswa' => $siswa->id_siswa,
                                'id_mapel' => $mapel->id_mapel,
                                'nama_tugas' => $newName,
                                'status' => 'Belum Selesai',
                            ]);
                        }
                    }
                }

                $uniqueTasksCount = RekapPengumpulan::where('id_rekap_kelas', $classId)
                    ->distinct('nama_tugas')
                    ->count('nama_tugas');

                $siswaDiKelas = Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
                $jumlahSiswaSelesaiSemua = 0;
                $jumlahSiswaBelumSelesai = 0;

                if ($uniqueTasksCount > 0) {
                    foreach ($siswaDiKelas as $idSiswa) {
                        $tugasSelesaiPerSiswa = RekapPengumpulan::where('id_rekap_kelas', $classId)
                            ->where('id_siswa', $idSiswa)
                            ->where('status', 'Selesai')
                            ->count();

                        if ($tugasSelesaiPerSiswa >= $uniqueTasksCount) {
                            $jumlahSiswaSelesaiSemua++;
                        } else {
                            $jumlahSiswaBelumSelesai++;
                        }
                    }
                }

                $rekap->total_tugas = $uniqueTasksCount;
                $rekap->jumlah_selesai = $jumlahSiswaSelesaiSemua;
                $rekap->jumlah_tanggungan = $jumlahSiswaBelumSelesai;
                $rekap->save();

                $results[$classId] = 'Berhasil diupdate';
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Semua tugas berhasil diupdate',
                'results' => $results
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate semua tugas: ' . $e->getMessage()
            ], 500);
        }
    }


    // public function generateAllTasks(Request $request)
    // {
    //     $rekapList = RekapKelas::all();

    //     foreach ($rekapList as $rekap) {
    //         $mapel = $rekap->mapel;
    //         $kelas = $rekap->kelas;
    //         $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();
    //         $tasks = $request->tasks[$rekap->id_rekap] ?? [];

    //         $totalTugas = 0; // Menghitung total tugas baru

    //         foreach ($siswaList as $siswa) {
    //             foreach ($tasks as $taskName) {
    //                 RekapPengumpulan::create([
    //                     'id_rekap_kelas' => $rekap->id_rekap,
    //                     'id_siswa' => $siswa->id_siswa,
    //                     'id_mapel' => $mapel->id_mapel,
    //                     'nama_tugas' => $taskName,
    //                     'status' => 'Belum Selesai',
    //                 ]);
    //                 $totalTugas++;
    //             }
    //         }

    //         // Update total tugas di rekap_kelas
    //         $rekap->total_tugas += $totalTugas;
    //         $rekap->jumlah_selesai = RekapPengumpulan::where('id_mapel', $mapel->id_mapel)
    //             ->whereHas('siswa', function ($query) use ($kelas) {
    //                 $query->where('id_kelas', $kelas->id_kelas);
    //             })->where('status', 'Selesai')->count();

    //         $rekap->jumlah_tanggungan = $rekap->total_tugas - $rekap->jumlah_selesai;
    //         $rekap->save();
    //     }

    //     return response()->json(['message' => 'Semua tugas berhasil dibuat dan total tugas diperbarui'], 200);
    // }
}
