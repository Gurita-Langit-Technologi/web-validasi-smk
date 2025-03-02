<?php

namespace App\Http\Controllers;

use App\Models\RekapKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function index()
    {
        // Ambil guru yang sedang login
        $guru = Auth::guard('guru')->user();
        $rekapKelas = RekapKelas::with(['kelas', 'mapel', 'guru'])
            ->where('id_guru', $guru->id_guru)
            ->get();

        return view('pages.app.page-kelas', compact('rekapKelas'));
    }
}
