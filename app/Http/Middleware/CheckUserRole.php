<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the authenticated user from the 'customer' guard
        $user = auth('customer')->user();

        // Check if the user has 'is_pro', 'is_vip', or 'is_adult' set to true (1)
        if ($user && ($user->is_pro == 1 || $user->is_vip == 1 || $user->is_adult == 1)) {
            // If the user has any of these roles, allow the request to continue
            return $next($request);
        }

        // If the user does not have the required roles, redirect them to the home page
        return redirect('/')->with('error', 'You do not have the required permissions to access this page.');
    }
}
