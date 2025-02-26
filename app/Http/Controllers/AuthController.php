<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        $token = Str::random(60);

        Mail::send('emails.reset-password', ['token' => $token], function ($message) use ($guru) {
            $message->to($guru->email);
            $message->subject('Reset Password Anda');
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
