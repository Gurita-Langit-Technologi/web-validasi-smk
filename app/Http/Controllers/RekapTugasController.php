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
            'kelas.waliKelas',
            'mapel',
            'guru'
        ])->get();

        $rekapTugas = collect();
        $guruIdLogin = Auth::guard('guru')->user()->id_guru;

        foreach ($tugasMengajar as $mengajar) {
            $rekap = RekapKelas::firstOrCreate(
                [
                    'id_kelas' => $mengajar->id_kelas,
                    'id_mapel' => $mengajar->id_mapel,
                    'id_guru' => $mengajar->id_guru,
                ],
                [
                    'id_wali_kelas' => $mengajar->kelas->id_wali_kelas,
                    'total_tugas' => 0,
                    'jumlah_selesai' => 0,
                    'jumlah_tanggungan' => 0,
                ]
            );

            $rekap->load(['kelas.waliKelas', 'mapel', 'guru', 'tugas']);

            $rekap->total_tugas = $rekap->tugas->unique('nama_tugas')->count();

            $jumlahSelesai = RekapPengumpulan::where('id_rekap_kelas', $rekap->id_rekap_kelas)
                ->where('status', 'Selesai')
                ->count();

            $rekap->jumlah_selesai = $jumlahSelesai;
            $rekap->jumlah_tanggungan = $rekap->total_tugas - $jumlahSelesai;
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

        return redirect()->route('rekap-tugas')->with('success', 'Data berhasil diperbarui!');
    }

    public function showDetailTugas($id_mapel)
    {
        $mapel = Mapel::find($id_mapel);
        $rekapKelas = RekapKelas::with('kelas')->where('id_mapel', $id_mapel)->first();

        $siswa = Siswa::whereIn('id_siswa', function ($query) use ($id_mapel) {
            $query->select('id_siswa')
                ->from('rekap_pengumpulan')
                ->where('id_mapel', $id_mapel);
        })->get();

        $tugas = RekapPengumpulan::whereIn('id_tugas', function ($query) use ($id_mapel) {
            $query->selectRaw('MIN(id_tugas)')
                ->from('rekap_pengumpulan')
                ->where('id_mapel', $id_mapel)
                ->groupBy('nama_tugas');
        })->get();

        $siswaTugas = [];
        foreach ($siswa as $student) {
            $siswaTugas[$student->id_siswa] = RekapPengumpulan::where('id_mapel', $id_mapel)
                ->where('id_siswa', $student->id_siswa)
                ->select('id_tugas', 'nama_tugas')
                ->groupBy('id_tugas', 'nama_tugas')
                ->get();
        }


        $siswaStatus = [];

        foreach ($siswa as $siswas) {
            $completedTasks = RekapPengumpulan::where('id_siswa', $siswas->id_siswa)
                ->where('id_mapel', $id_mapel)
                ->where('status', 'Selesai')
                ->count();

            $totalTasks = RekapPengumpulan::where('id_siswa', $siswas->id_siswa)
                ->where('id_mapel', $id_mapel)
                ->count();

            if ($totalTasks == 0) {
                $status = 'danger';
            } elseif ($completedTasks == $totalTasks) {
                $status = 'success';
            } elseif ($completedTasks > 0) {
                $status = 'warning';
            } else {
                $status = 'danger';
            }

            $siswaStatus[$siswas->id_siswa] = [
                'status' => $status,
                'completed' => $completedTasks,
                'total' => $totalTasks,
            ];
        }

        return view('pages.app.ceklis-tugas', compact('mapel', 'tugas', 'siswa', 'siswaTugas', 'siswaStatus', 'rekapKelas'));
    }


    public function updateStatusTugas(Request $request)
    {
        $namaTugasBaru = $request->input('nama_tugas_baru');
        if ($namaTugasBaru) {
            $siswaList = is_array($request->id_siswa) ? array_unique($request->id_siswa) : [$request->id_siswa];

            // dd($siswaList);

            foreach ($siswaList as $siswaId) {
                RekapPengumpulan::create([
                    'nama_tugas' => $namaTugasBaru,
                    'id_mapel' => $request->id_mapel,
                    'id_rekap_kelas' => $request->id_rekap_kelas,
                    'id_siswa' => $siswaId,
                ]);
            }
        }

        foreach ($request->tugas as $siswaId => $tugas) {
            foreach ($tugas as $tugasId => $status) {


                $rekap = RekapPengumpulan::where('id_tugas', $tugasId)
                    ->where('id_siswa', $siswaId)
                    ->first();

                if ($rekap) {
                    $rekap->status = $status ?? 'Belum Selesai';
                    $rekap->tanggal_pengumpulan = $request->tanggal_pengumpulan[$siswaId][$tugasId] ?? null;
                    $rekap->keterangan = $request->keterangan[$siswaId][$tugasId] ?? null;
                    $rekap->nilai = $request->nilai[$siswaId][$tugasId] ?? null;
                    $rekap->save();
                }
            }

            $totalTugas = RekapPengumpulan::where('id_siswa', $siswaId)->count();
            $tugasSelesai = RekapPengumpulan::where('id_siswa', $siswaId)
                ->where('status', 'Selesai')
                ->count();
            $jumlahTanggungan = $totalTugas - $tugasSelesai;

            RekapKelas::where('id_mapel', $request->id_mapel)->update([
                'jumlah_selesai' => $tugasSelesai,
                'jumlah_tanggungan' => $jumlahTanggungan,
            ]);
        }

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function addSingleTaskPerClass(Request $request, $id_rekap)
    {
        $rekap = RekapKelas::findOrFail($id_rekap);
        $mapel = $rekap->mapel;
        $kelas = $rekap->kelas;
        $taskName = $request->task;

        if (!$taskName) {
            return response()->json(['message' => 'Nama tugas tidak boleh kosong!'], 400);
        }

        $siswaList = Siswa::where('nama_kelas', $kelas->nama_kelas)->get();

        foreach ($siswaList as $siswa) {
            RekapPengumpulan::create([
                'id_rekap_kelas' => $id_rekap,
                'id_siswa' => $siswa->id_siswa,
                'id_mapel' => $mapel->id_mapel,
                'nama_tugas' => $taskName,
                'status' => 'Belum Selesai',
            ]);
        }

        $rekap->total_tugas += 1;
        $rekap->jumlah_selesai = RekapPengumpulan::where('id_mapel', $mapel->id_mapel)
            ->whereHas('siswa', function ($query) use ($kelas) {
                $query->where('nama_kelas', $kelas->nama_kelas);
            })->where('status', 'Selesai')->count();

        $rekap->jumlah_tanggungan = $rekap->total_tugas - $rekap->jumlah_selesai;
        $rekap->save();

        return response()->json(['message' => 'Tugas baru berhasil ditambahkan!'], 200);
    }


    public function generateTasksPerClass(Request $request, $id_rekap)
    {
        $rekap = RekapKelas::findOrFail($id_rekap);
        $mapel = $rekap->mapel;
        $kelas = $rekap->kelas;
        $tasks = $request->tasks;

        DB::beginTransaction();
        try {
            foreach ($tasks as $taskData) {
                $oldName = $taskData['old_name'];
                $newName = $taskData['new_name'];

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

            $jumlahSelesai = RekapPengumpulan::where('id_rekap_kelas', $id_rekap)
                ->where('status', 'Selesai')
                ->count();

            $rekap->total_tugas = $uniqueTasksCount;
            $rekap->jumlah_selesai = $jumlahSelesai;
            $rekap->jumlah_tanggungan = $uniqueTasksCount - $jumlahSelesai;
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
        $allTasks = $request->tasks;
        $results = [];

        DB::beginTransaction();
        try {
            foreach ($allTasks as $classId => $tasks) {
                $rekap = RekapKelas::findOrFail($classId);
                $mapel = $rekap->mapel;
                $kelas = $rekap->kelas;

                foreach ($tasks as $taskData) {
                    $oldName = $taskData['old_name'];
                    $newName = $taskData['new_name'];

                    if (empty($newName)) {
                        continue;
                    }

                    if ($oldName) {
                        // Update existing task
                        RekapPengumpulan::where('id_rekap_kelas', $classId)
                            ->where('nama_tugas', $oldName)
                            ->update(['nama_tugas' => $newName]);
                    } else {
                        // Create new tasks for all students
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

                // Update total tugas
                $uniqueTasksCount = RekapPengumpulan::where('id_rekap_kelas', $classId)
                    ->distinct('nama_tugas')
                    ->count('nama_tugas');

                $rekap->total_tugas = $uniqueTasksCount;
                $rekap->jumlah_selesai = RekapPengumpulan::where('id_rekap_kelas', $classId)
                    ->where('status', 'Selesai')
                    ->count();
                $rekap->jumlah_tanggungan = $rekap->total_tugas - $rekap->jumlah_selesai;
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
    //         $siswaList = Siswa::where('nama_kelas', $kelas->nama_kelas)->get();
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
