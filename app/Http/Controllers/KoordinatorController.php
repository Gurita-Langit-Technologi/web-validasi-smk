<?php

namespace App\Http\Controllers;

use App\Models\RekapPengumpulan;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KoordinatorController extends Controller
{
    public function dashboard(Request $request)
    {
        $siswa = null;
        $tugasSiswa = collect();

        if ($request->has('no_induk') || $request->has('nama_siswa')) {
            $query = Siswa::query();

            if ($request->filled('no_induk')) {
                $query->where('no_induk', 'like', '%' . $request->no_induk . '%');
            }

            if ($request->filled('nama_siswa')) {
                $query->where('nama_siswa', 'like', '%' . $request->nama_siswa . '%');
            }

            $siswa = $query->with('kelas')->first();

            if ($siswa) {
                $tugasSiswa = RekapPengumpulan::with('mapel')
                    ->where('id_siswa', $siswa->id_siswa)
                    ->orderBy('id_mapel')
                    ->orderBy('nama_tugas')
                    ->get();
            }
        }

        return view('pages.dashboard.dashboard-koordinator', compact('siswa', 'tugasSiswa'));
    }
}
