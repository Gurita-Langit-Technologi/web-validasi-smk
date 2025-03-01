<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\UserGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Resend\Laravel\Facades\Resend;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'kode_guru' => 'required|string',
            'password' => 'required',
        ]);

        $guru = Guru::where('kode_guru', $request->kode_guru)->first();
        $user_guru = UserGuru::where('guru_id', $guru->id_guru)->first();

        // dd($guru && Hash::check($request->password, $user_guru->password));

        if ($guru && Hash::check($request->password, $user_guru->password)) {
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

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:guru,email',
        ]);

        $guru = Guru::where('email', $request->email)->first();

        if (!$guru) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $guru->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        Resend::emails()->send([
            'from' => 'noreply@yourdomain.com',
            'to' => $guru->email,
            'subject' => 'Reset Password',
            'html' => "Klik link berikut untuk mereset password Anda: <a href='" . route('reset-password-form', ['token' => $token]) . "'>Reset Password</a>"
        ]);

        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $guru = Guru::where('reset_token', $request->token)->first();

        if (!$guru) {
            return back()->withErrors(['error' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        // Update password
        $guru->password = Hash::make($request->password);
        $guru->save();

        return redirect()->route('login-form')->with('status', 'Password Anda telah berhasil direset.');
    }
}
