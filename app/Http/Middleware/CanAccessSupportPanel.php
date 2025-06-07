<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanAccessSupportPanel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();
        if (!$user) {
            // Redirect to a 403 Forbidden page or any other appropriate action
            return abort(403, 'Unauthorized action.');
        }

        $ACCEPTED_ROLES = ['admin', 'support'];

        if (!in_array($user->role, $ACCEPTED_ROLES)) {
            // Redirect to a 403 Forbidden page or any other appropriate action
            return abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
