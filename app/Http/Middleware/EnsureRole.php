<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate a route group to one or more roles. Mirrors the legacy per-folder
 * `check_session($role)` guard, but centralized.
 *
 * Usage: ->middleware('role:lgu')  or  ->middleware('role:admin,super_admin')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole(...$roles)) {
            abort(403, 'You do not have access to this area.');
        }

        return $next($request);
    }
}
