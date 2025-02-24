<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required',
        ]);

        $guru = Guru::where('nip', $request->nip)->first();
        // dd($guru && Hash::check($request->password, $guru->password));
        if ($guru && Hash::check($request->password, $guru->password)) {
            Auth::guard('guru')->login($guru);
            return redirect()->route('guru.dashboard');
        }


        return back()->withErrors(['login' => 'Login gagal, periksa kembali kredensial Anda.']);
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login-form');
    }
}
