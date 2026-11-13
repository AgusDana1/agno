<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Anda tidak berhak masuk kesini!');
        }

        $role = $user->role?->name;

        if (! $role || ! in_array($role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses untuk halaman ini!');
        }

        return $next($request);
    }
}
