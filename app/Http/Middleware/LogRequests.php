<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LogRequests
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
        // Verificar autenticación una sola vez
        $isAuthenticated = Auth::check();
        $userId = $isAuthenticated ? Auth::id() : 'no autenticado';

        // Solo un log detallado por solicitud
        if ($request->method() !== 'GET' || strpos($request->path(), 'api') === 0) {
            Log::debug('Solicitud detallada', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => $userId,
                'ip' => $request->ip(),
                'headers' => $request->headers->all(),
            ]);
        }

        // Procesar la solicitud
        $response = $next($request);

        // Registrar la respuesta
        // Verificar el tipo de respuesta antes de intentar acceder a status()
        if ($response instanceof BinaryFileResponse) {
            // Para respuestas de archivo binario (descargas)
            Log::info('Respuesta exitosa', [
                'status_code' => $response->getStatusCode(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        } else {
            // Para respuestas normales
            Log::info('Respuesta exitosa', [
                'status_code' => $response->status(),
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);
        }

        return $response;
    }
}
