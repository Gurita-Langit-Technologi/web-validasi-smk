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
use Illuminate\Support\Facades\Schema;

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

        $user_guru = UserGuru::updateOrCreate(
            ['guru_id' => $guru->id_guru],
            [
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

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

        // Get email from UserGuru
        $userGuru = UserGuru::where('guru_id', $guru->id_guru)->first();
        $email = $userGuru->email ?? null;

        return view('pages.dashboard.dashboard-guru', [
            'isWaliKelas' => false,
            'guru' => $guru,
            'email' => $email
        ]);
    }

    public function updateProfile(Request $request)
    {
        $guru = Auth::guard('guru')->user();

        $request->validate([
            'nama_guru' => 'required|string|max:80',
            'email' => 'required|email|unique:user_guru,email,' . $guru->id_guru . ',guru_id',
            'no_telepon' => 'nullable|string|max:20',
            'foto_guru' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update nama_guru
        $guru->nama_guru = $request->nama_guru;

        // Update no_telepon if column exists
        if (Schema::hasColumn('guru', 'no_telepon')) {
            $guru->no_telepon = $request->no_telepon;
        }

        // Handle foto upload
        if ($request->hasFile('foto_guru')) {
            // Hapus foto lama jika ada
            if ($guru->foto_guru && Storage::disk('public')->exists($guru->foto_guru)) {
                Storage::disk('public')->delete($guru->foto_guru);
            }

            $file = $request->file('foto_guru');
            $filename = 'guru_' . $guru->id_guru . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan ke storage dengan disk 'public'
            $path = $file->storeAs('guru', $filename, 'public');
            $guru->foto_guru = $path;
        }

        $guru->save();

        // Update atau buat UserGuru
        $userGuru = UserGuru::where('guru_id', $guru->id_guru)->first();

        if ($userGuru) {
            $userGuru->email = $request->email;
            $userGuru->save();
        } else {
            $userGuru = new UserGuru;
            $userGuru->guru_id = $guru->id_guru;
            $userGuru->email = $request->email;
            // Jika perlu password default atau field lain
            $userGuru->save();
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto_guru' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $guru = Auth::guard('guru')->user();

        try {


            // Hapus foto lama jika ada di disk public
            if ($guru->foto_guru && Storage::disk('public')->exists($guru->foto_guru)) {
                Storage::disk('public')->delete($guru->foto_guru);
            }

            // Upload foto baru ke disk public
            $file = $request->file('foto_guru');
            $filename = 'guru_' . $guru->id_guru . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('guru', $filename, 'public');

            // Simpan path ke database
            $guru->foto_guru = $path;
            $guru->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diubah!',
                'photo_url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah foto profil: ' . $e->getMessage()
            ], 500);
        }
    }
}
