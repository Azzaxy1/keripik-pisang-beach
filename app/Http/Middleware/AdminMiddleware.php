<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has admin role using Spatie Permission
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Alternative check: if the user is created as admin (fallback)
        if ($user->email === 'admin@example.com' || $user->email === 'admin@ecommerce.com') {
            return $next($request);
        }

        abort(403, 'Access denied. Admin privileges required.');
    }
}
