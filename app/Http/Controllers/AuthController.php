<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login()
{
    // Paksa logout jika user mengunjungi halaman login
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return view('login');
}

public function authenticate(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Redirect berdasarkan role
        if (Auth::user()->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard'); // Dashboard superadmin (kosong)
        } else {
            return redirect()->route('dashboard'); // Dashboard admin (tetap yang lama)
        }
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->withInput();
}
}
