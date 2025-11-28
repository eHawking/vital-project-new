<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if the request is for admin routes
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            
            // For user routes, redirect to main script home
            $mainScriptUrl = rtrim(env('MAIN_SCRIPT_URL', 'https://dewdropskin.com'), '/');
            return $mainScriptUrl;
        }
    }
}
