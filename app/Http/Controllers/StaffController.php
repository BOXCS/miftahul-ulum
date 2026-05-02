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

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:superadmin,admin',
        ]);

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
            return back()->withErrors(['error' => 'Tidak dapat mengubah data akun sendiri di sini. Gunakan halaman Profil.']);
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => 'required|in:superadmin,admin',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('staff.index')
            ->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->authorizeSuperAdmin();

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri.']);
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
