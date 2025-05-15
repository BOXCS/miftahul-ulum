<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Akun; // Ganti dengan model 'Akun' jika nama tabelnya 'akun'
use App\Models\OrangTua;
use App\Models\Staff;
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
    // Validasi input
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
    ]);

    $credentials = $request->only('email', 'password');

    Log::debug('Autentikasi dimulai', ['data' => $credentials]);

    // Coba login langsung dengan Auth::attempt
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->hak_akses === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->hak_akses === 'admin') {
            return redirect()->route('dashboard');
        }

        return redirect('/');
    }

    // Jika sampai sini berarti login gagal
    Log::error('Login Error: Kredensial tidak valid', ['email' => $request->email]);
    
    return back()->withInput()->withErrors([
        'email' => 'Email atau password salah',
    ])->with('error', 'Email atau password salah');
}



    public function registerForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:akun,username',
            'email' => 'required|email|unique:akun,email',
            'password' => 'required|min:6',
            'hak_akses' => 'required|in:admin,ortu',
        ]);

        // Simpan akun di tabel akun
        $akun = Akun::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'hak_akses' => $request->hak_akses,
        ]);

        // Jika yang mendaftar adalah orang tua
        if ($request->hak_akses === 'ortu') {
            $request->validate([
                'alamat_ortu' => 'required',
                'no_telp_ortu' => 'required',
            ]);

            OrangTua::create([
                'id_akun' => $akun->id_akun,
                'alamat' => $request->alamat_ortu,
                'no_telp' => $request->no_telp_ortu,
            ]);
        }

        // Jika yang mendaftar adalah staff (admin)
        if ($request->hak_akses === 'admin') {
            $request->validate([
                'nama_staff' => 'required',
                'alamat_staff' => 'required',
                'no_telp_staff' => 'required',
                'jabatan_staff' => 'required',
                'tgl_bergabung_staff' => 'required|date',
            ]);

            Staff::create([
                'id_akun' => $akun->id_akun,
                'nama' => $request->nama_staff,
                'alamat' => $request->alamat_staff,
                'no_telp' => $request->no_telp_staff,
                'jabatan' => $request->jabatan_staff,
                'tgl_bergabung' => $request->tgl_bergabung_staff,
            ]);
        }

        return redirect()->route('register')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function showSuperadminForm()
    {
        return view('register_superadmin');
    }

    public function registerSuperadmin(Request $request)
    {
        // Pastikan fitur ini hanya bisa digunakan saat pertama kali
        $existingSuperadmin = Akun::where('hak_akses', 'superadmin')->first();
        if ($existingSuperadmin) {
            return redirect('/')->with('error', 'Superadmin sudah terdaftar!');
        }

        $request->validate([
            'username' => 'required|unique:akun,username',
            'email' => 'required|email|unique:akun,email',
            'password' => 'required|min:6',
        ]);

        $akun = Akun::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'hak_akses' => 'superadmin',
        ]);

        Staff::create([
            'id_akun' => $akun->id_akun,
            'nama' => $request->nama_staff,
            'alamat' => $request->alamat_staff,
            'no_telp' => $request->no_telp_staff,
            'jabatan' => $request->jabatan_staff,
            'tgl_bergabung' => $request->tgl_bergabung_staff,
        ]);

        return redirect('/register')->with('success', 'Superadmin berhasil didaftarkan! Silakan login.');
    }
}
