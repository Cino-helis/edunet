<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->type_utilisateur;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}