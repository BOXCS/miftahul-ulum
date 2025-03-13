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

        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized action.'); // Jika bukan role yang diizinkan
        }

        return $next($request);
    }
}
