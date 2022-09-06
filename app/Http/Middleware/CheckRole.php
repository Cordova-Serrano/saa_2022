<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckRole 
{
    public function handle(Request $request, Closure $next,  ...$roles) 
    {
        $userRoles = Auth::user()->roles->pluck('name');
        // dd($userRoles);
        if(!$userRoles->contains('super')){
            return redirect('/home');
        }

        return $next($request);
    }
}