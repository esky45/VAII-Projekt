<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is an admin
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return redirect('/');
        }

        return $next($request);
    }
}