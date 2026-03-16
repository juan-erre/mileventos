<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizadoresMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!($user->isAdmin() || $user->isOrganizador())) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
