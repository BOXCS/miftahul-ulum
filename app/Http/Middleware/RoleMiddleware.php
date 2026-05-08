<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
{
    logger()->info('User hak_akses: ' . Auth::user()->hak_akses);
    logger()->info('Roles yang diterima middleware:', $roles);

    if (!in_array(Auth::user()->hak_akses, $roles)) {
        abort(403, 'Unauthorized.');
    }

    return $next($request);
}


}
