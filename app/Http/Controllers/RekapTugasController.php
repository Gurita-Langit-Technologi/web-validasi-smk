<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\RekapKelas;
use App\Models\Siswa;
use App\Models\RekapPengumpulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapTugasController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        $rekapTugas = RekapKelas::with(['kelas', 'mapel', 'guru'])
            ->where('id_guru', $guru->id_guru)
            ->get();
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
        $siswa = Siswa::whereIn('id_siswa', function ($query) use ($id_mapel) {
            $query->select('id_siswa')
                ->from('rekap_pengumpulan')
                ->where('id_mapel', $id_mapel);
        })->get();

        $tugas = RekapPengumpulan::where('id_mapel', $id_mapel)
            ->selectRaw('MIN(id_tugas) as id_tugas, nama_tugas')
            ->groupBy('nama_tugas')
            ->get();


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

        return view('pages.app.ceklis-tugas', compact('mapel', 'tugas', 'siswa', 'siswaStatus'));
    }



    public function updateStatusTugas(Request $request)
    {
        foreach ($request->tugas as $siswaId => $tugas) {
            foreach ($tugas as $tugasId => $status) {
                $rekap = RekapPengumpulan::where('id_tugas', $tugasId)
                    ->where('id_siswa', $siswaId)
                    ->first();

                if ($rekap) {
                    $rekap->status = $status;
                    $rekap->tanggal_pengumpulan = $request->tanggal_pengumpulan[$siswaId][$tugasId] ?? null;
                    $rekap->keterangan = $request->keterangan[$siswaId][$tugasId] ?? null;
                    $rekap->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }







    public function generateTasksPerClass(Request $request, $id_rekap)
    {
        $rekap = RekapKelas::findOrFail($id_rekap);
        $mapel = $rekap->mapel;
        $kelas = $rekap->kelas;

        // Ambil semua siswa dalam kelas yang bersangkutan
        $siswaList = Siswa::where('nama_kelas', $kelas->nama_kelas)->get();

        // Ambil tugas dari request
        $tasks = $request->tasks;

        foreach ($siswaList as $siswa) {
            foreach ($tasks as $taskName) {
                RekapPengumpulan::create([
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
                    RekapPengumpulan::create([
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
