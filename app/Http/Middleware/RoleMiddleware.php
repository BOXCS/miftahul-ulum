<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login'); // Redirect ke login jika belum login
        }

        // Cek apakah user memiliki hak akses yang sesuai
        if (Auth::user()->hak_akses !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
