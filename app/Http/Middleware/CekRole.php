<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $role = auth()->user()->role ?? null;

        if ($role === 'admin' || in_array($role, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki hak akses.');
    }
}