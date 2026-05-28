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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! empty($roles) && ! $request->user()->hasAnyRole($roles)) {
            // [role:user] redirect to their allowed landing page instead of hard 403
            if ($request->user()->hasRole('user')) {
                return redirect()->route('leases.index');
            }
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
