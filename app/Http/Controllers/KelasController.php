<?php

namespace App\Http\Controllers;

use App\Models\RekapKelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $rekapKelas = RekapKelas::with(['kelas', 'mapel', 'guru'])->get();
        return view('pages.app.page-kelas', compact('rekapKelas'));
    }
}
