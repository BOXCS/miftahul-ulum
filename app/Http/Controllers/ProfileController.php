<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        return view('profil.user', compact('user'));
    }

    /**
     * Tampilkan halaman pengaturan (keamanan/password).
     */
    public function settings()
    {
        $user = Auth::user();

        return view('profil.settings', compact('user'));
    }

    /**
     * Update informasi pribadi (nama & email).
     */
    public function update(Request $request, $id)
    {
        if ((int) $id !== (int) Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update kata sandi.
     */
    public function updatePassword(Request $request, $id)
    {
        if ((int) $id !== (int) Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Kata sandi berhasil diubah.');
    }
}
