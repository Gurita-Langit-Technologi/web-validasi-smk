<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
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
            'nip' => 'required|exists:guru,nip',
            'email' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $guru = Guru::where('nip', $request->nip)->first();
        if (!$guru) {
            return back()->withErrors(['nip' => 'NIP tidak ditemukan']);
        }

        $guru->email = $request->email;
        $guru->password = Hash::make($request->password);
        $guru->save();

        return redirect()->route('login-form')->with('success', 'Password berhasil dibuat, silakan login.');
    }
}
