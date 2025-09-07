<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\UserGuru;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Resend\Laravel\Facades\Resend;

class AuthController extends Controller
{
    public function showGuruLoginForm()
    {
        return view('auth.login');
    }

    public function showWaliLoginForm()
    {
        return view('auth.login_wali');
    }

    // public function showKoordinatorLoginForm()
    // {
    //     return view('auth.login-koordinator');
    // }

    // public function loginKoordinator(Request $request)
    // {
    //     $request->validate([
    //         'kode_wali' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $koordinator = WaliKelas::where('kode_wali', $request->kode_wali)
    //         ->where('role', 'koordinator')
    //         ->first();

    //     if ($koordinator && Hash::check($request->password, $koordinator->password)) {
    //         Auth::guard('wali')->login($koordinator);
    //         return redirect()->route('koordinator.dashboard');
    //     }

    //     return back()->withErrors(['login.koordinator.form' => 'Kode atau password salah']);
    // }


    public function loginGuru(Request $request)
    {
        $request->validate([
            'kode_guru' => 'required',
            'password' => 'required',
        ]);

        $guru = Guru::where('kode_guru', $request->kode_guru)->first();
        $user = UserGuru::where('guru_id', optional($guru)->id_guru)->first();

        if ($guru && $user && Hash::check($request->password, $user->password)) {
            Auth::guard('guru')->login($guru);
            return redirect()->route('guru.dashboard');
        }

        return back()->withErrors(['login.guru.form' => 'Kode atau password salah']);
    }

    public function loginWali(Request $request)
    {
        $request->validate([
            'kode_wali' => 'required',
            'password' => 'required',
        ]);

        $wali = WaliKelas::where('kode_wali', $request->kode_wali)->first();

        if ($wali && $wali->role == 'wali kelas' && Hash::check($request->password, $wali->password)) {
            Auth::guard('wali')->login($wali);
            return redirect()->route('wali.dashboard');
        }

        return back()->withErrors(['login.wali.form' => 'Kode atau password salah']);
    }


    public function logout(Request $request)
    {
        if (Auth::guard('guru')->check()) {
            Auth::guard('guru')->logout();
        } elseif (Auth::guard('wali')->check()) {
            Auth::guard('wali')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function logoutGuru(Request $request)
    {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.guru.form')->with('success', 'Anda telah berhasil logout.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user_guru,email',
        ]);

        $guru = UserGuru::where('email', $request->email)->first();

        if (!$guru) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $guru->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        Mail::send('mails.link-reset-password', ['token' => $token], function ($message) use ($guru) {
            $message->to($guru->email)
                ->subject('Reset Password');
        });

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
