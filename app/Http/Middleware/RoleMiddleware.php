<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // Check if the user is logged in and has the correct role
        if (!Auth::check() || !in_array(Auth::user()->role, explode('|', $role))) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}