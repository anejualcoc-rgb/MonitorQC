<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

        if ($user->role === 'spv') {
            return redirect()->route('dashboard_spv')
                ->with('success', 'Selamat datang Supervisor QC!');
                
        } elseif ($user->role === 'staff') {
            return redirect()->route('dashboard_staff')
                ->with('success', 'Selamat datang Staff QC!');
                
        } elseif ($user->role === 'manager') {
            return redirect()->route('dashboard_manager')
                ->with('success', 'Selamat datang Manager QC!');
        }

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
