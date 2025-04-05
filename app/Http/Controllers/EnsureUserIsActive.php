<?php

// app/Http/Middleware/EnsureUserIsActive.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();

            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        return $next($request);
    }
}
