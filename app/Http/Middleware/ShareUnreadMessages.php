<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareUnreadMessages
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Aquí puedes obtener el número de mensajes no leídos de tu modelo de mensajes
            // Por ahora lo dejamos en 0
            view()->share('unreadMessages', 0);
        }

        return $next($request);
    }
}
