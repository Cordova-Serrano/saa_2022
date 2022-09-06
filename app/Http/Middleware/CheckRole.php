<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next,  string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($request->user()->role === $role) {
            return $next($request);
        }

        return redirect()->route('home')->with('warning', 'Ha intentado acceder a una sección a la cual no está autorizado.');
    }
}
