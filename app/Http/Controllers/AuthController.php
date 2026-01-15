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
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 2. Proses Registrasi
    public function register(Request $request)
    {
        // Validasi Input
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email', // Cek email unik di tabel users
            'password'  => 'required|min:6|confirmed', // 'confirmed' menuntut adanya input name="password_confirmation"
            'role'      => 'required|in:staff,spv,manager', // Batasi pilihan role
        ], [
            'email.unique'       => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.'
        ]);

        // Buat User Baru
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role'     => $request->role,
        ]);

        // Redirect ke Login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
