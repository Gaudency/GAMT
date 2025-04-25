<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            \Log::info('Redirigiendo a login desde Authenticate middleware', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_authenticated' => auth()->check(),
                'session_has_auth' => $request->session()->has('auth')
            ]);
            return route('login');
        }
        return null;
    }
}
