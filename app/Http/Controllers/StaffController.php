<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::whereIn('role', ['superadmin', 'admin'])
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        $stats = [
            'total'      => $staff->count(),
            'superadmin' => $staff->where('role', 'superadmin')->count(),
            'admin'      => $staff->where('role', 'admin')->count(),
        ];

        return view('staff.index', compact('staff', 'stats'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $messages = [
            'password.confirmed' => 'Password dan Konfirmasi Password tidak cocok!',
            'password.min'       => 'Password terlalu pendek, minimal 8 karakter.',
        ];

        // Cek apakah email sudah terdaftar (agar muncul pop-up error spesifik)
        $existingEmail = User::where('email', $request->email)->first();
        if ($existingEmail) {
            return back()->with('error', 'Gagal menambahkan! Email tersebut sudah terdaftar di sistem.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:superadmin,admin',
        ], $messages);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $user = User::findOrFail($id);

        // Superadmin tidak boleh mengubah role dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat mengubah data akun sendiri di sini. Gunakan halaman Profil.');
        }

        // Cek apakah email sudah terdaftar pada user lain
        $existingEmail = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
        if ($existingEmail) {
            return back()->with('error', 'Gagal memperbarui! Email tersebut sudah digunakan oleh staff lain.');
        }

        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'role'     => 'required|in:superadmin,admin',
            'password' => 'required|string|min:8|confirmed',
        ];

        $messages = [
            'password.confirmed' => 'Password Baru dan Konfirmasi Password tidak cocok!',
            'password.min'       => 'Password Baru terlalu pendek, minimal 8 karakter.',
        ];

        $request->validate($rules, $messages);

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->role     = $request->role;
        $user->password = bcrypt($request->password);

        $user->save();

        return redirect()->route('staff.index')
            ->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->authorizeSuperAdmin();

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil dihapus.');
    }

    private function authorizeSuperAdmin(): void
    {
        if (! Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat melakukan aksi ini.');
        }
    }
}
