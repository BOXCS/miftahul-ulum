<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Akun; // Ganti dengan model 'Akun' jika nama tabelnya 'akun'
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        Log::debug('Autentikasi dimulai', ['data' => $credentials]);

        // Cek apakah username ada
        $user = Akun::where('username', $request->username)->first();
        if (!$user) {
            Log::error('Login Error: Username tidak ditemukan', ['username' => $request->username]);
            return back()->with('error', 'Username tidak ditemukan');
        }

        // Cek apakah password cocok
        if (!Hash::check($request->password, $user->password)) {
            Log::error('Login Error: Password salah', ['username' => $request->username]);
            return back()->with('error', 'Password salah');
        }

        // Coba login dengan Auth
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Ambil hak_akses user yang login
            $user = Auth::user();
    
            // Redirect berdasarkan hak_akses
            if ($user->hak_akses === 'superadmin') {
                return redirect()->route('superadmin.dashboard');
            } elseif ($user->hak_akses === 'admin') {
                return redirect()->route('dashboard');
            }
    
            return redirect('/');
        }

        Log::error('Login Error: Username tidak ditemukan', ['username' => $request->username]);

        return back()->with('error', 'Login gagal, silakan coba lagi.');
    }


    public function registerForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:akun', // Menggunakan tabel `akun`
            'password' => 'required|min:6|confirmed',
            'hak_akses' => 'required|in:admin,superadmin',
        ]);

        Akun::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Pastikan password dienkripsi dengan bcrypt
            'hak_akses' => $request->hak_akses,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}
