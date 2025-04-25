<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NoThrottleForLooseLoans
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rutas que queremos excluir del throttling
        $excludedPaths = [
            'loose-loans',
            'login',
            'register',
            'auth',
            'password',
        ];

        // Verificar si la ruta actual está en la lista de excluidas
        $currentPath = $request->path();

        foreach ($excludedPaths as $path) {
            if (Str::startsWith($currentPath, $path)) {
                // Para rutas excluidas, establecemos la tasa de throttle alta
                $request->headers->set('X-RateLimit-Bypass', 'true');
                break;
            }
        }

        return $next($request);
    }
}
