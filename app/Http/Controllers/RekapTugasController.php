<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\RekapKelas;
use App\Models\Siswa;
use App\Models\Tugas;
use Illuminate\Http\Request;

class RekapTugasController extends Controller
{
    public function index()
    {
        $rekapTugas = RekapKelas::with(['kelas', 'mapel', 'guru'])->get();
        return view('pages.app.page-tugas', compact('rekapTugas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_tugas' => 'required|integer|min:0',
            'jumlah_selesai' => 'required|integer|min:0',
        ]);

        $rekap = RekapKelas::findOrFail($id);
        $rekap->mapel->update([
            'total_tugas' => $request->total_tugas,
            'jumlah_selesai' => $request->jumlah_selesai,
            'jumlah_tanggungan' => $request->total_tugas - $request->jumlah_selesai,
        ]);

        return redirect()->route('rekap-tugas')->with('success', 'Data berhasil diperbarui!');
    }

    public function showDetailTugas($id_mapel)
    {
        $mapel = Mapel::find($id_mapel);
        $tugas = Tugas::where('id_mapel', $id_mapel)->get();
        $siswa = Siswa::all();

        $siswaStatus = [];

        foreach ($siswa as $siswas) {
            $completedTasks = Tugas::where('id_siswa', $siswas->id_siswa)->where('status', 'Selesai')->count();
            $totalTasks = Tugas::where('id_siswa', $siswas->id_siswa)->count();
            $remainingTasks = $totalTasks - $completedTasks;

            if ($completedTasks == $totalTasks) {
                $status = 'success';
            } elseif ($remainingTasks == 1) {
                $status = 'warning';
            } else {
                $status = 'danger';
            }

            $siswaStatus[$siswas->id_siswa] = $status;
        }

        // Kirim data ke view
        return view('pages.app.ceklis-tugas', compact('mapel', 'tugas', 'siswa', 'siswaStatus'));
    }

    public function generateTasksPerClass(Request $request, $id_rekap)
    {
        $rekap = RekapKelas::findOrFail($id_rekap);
        $mapel = $rekap->mapel;
        $kelas = $rekap->kelas;

        // Ambil semua siswa dalam kelas yang bersangkutan
        $siswaList = Siswa::where('kelas', $kelas->nama_kelas)->get();

        // Ambil tugas dari request
        $tasks = $request->tasks; // Array tugas dari frontend

        foreach ($siswaList as $siswa) {
            foreach ($tasks as $taskName) {
                Tugas::create([
                    'id_siswa' => $siswa->id_siswa,
                    'id_mapel' => $mapel->id_mapel,
                    'nama_tugas' => $taskName,
                    'status' => 'Belum Selesai',
                ]);
            }
        }

        return response()->json(['message' => 'Tugas berhasil dibuat untuk kelas ' . $kelas->nama_kelas], 200);
    }

    // Generate Tugas Untuk Semua Kelas
    public function generateAllTasks(Request $request)
    {
        $rekapList = RekapKelas::all();

        foreach ($rekapList as $rekap) {
            $mapel = $rekap->mapel;
            $kelas = $rekap->kelas;
            $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();
            $tasks = $request->tasks[$rekap->id_rekap] ?? []; // Ambil tugas berdasarkan id_rekap

            foreach ($siswaList as $siswa) {
                foreach ($tasks as $taskName) {
                    Tugas::create([
                        'id_siswa' => $siswa->id_siswa,
                        'id_mapel' => $mapel->id_mapel,
                        'nama_tugas' => $taskName,
                        'status' => 'Belum Selesai',
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Semua tugas berhasil dibuat untuk setiap kelas'], 200);
    }
}
