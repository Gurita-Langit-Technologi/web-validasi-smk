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
use Illuminate\Support\Facades\Storage;

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

        $user_wali = WaliKelas::where('id_guru', $guru->id_guru)->first();
        if ($user_wali) {
            $user_wali->password = Hash::make($request->password);
            $user_wali->save();
        }

        return redirect()->route('login.guru.form')->with('success', 'Password berhasil dibuat, silakan login.');
    }

    public function dashboard()
    {

        $guru = Auth::guard('guru')->user();
        return view('pages.dashboard.dashboard-guru', [
            'isWaliKelas' => false,
            'guru' => $guru
        ]);


        return redirect()->route('login.guru.form');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'foto_guru' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $guru = Auth::guard('guru')->user();

        // Hapus foto lama jika ada
        if ($guru->foto_guru && Storage::disk('public')->exists($guru->foto_guru)) {
            Storage::disk('public')->delete($guru->foto_guru);
        }

        // Upload foto baru
        if ($request->hasFile('foto_guru')) {
            $file = $request->file('foto_guru');
            $filename = 'guru_' . $guru->id_guru . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('guru_photos', $filename, 'public');

            $guru->foto_guru = $path;
            $guru->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
