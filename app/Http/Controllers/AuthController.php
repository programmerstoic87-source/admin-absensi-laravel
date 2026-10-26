<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
            }

            if (!in_array($user->jabatan, ['Admin', 'Supervisor', 'Chief'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda bukan admin.']);
            }

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
