<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Continuar con la solicitud sin duplicar el registro de entrada
        $response = $next($request);

        // Registrar información de la respuesta solo si hay errores
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            Log::error('Respuesta con error', [
                'status_code' => $statusCode,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id() ?? 'no autenticado',
                'response' => $response instanceof \Illuminate\Http\JsonResponse
                    ? json_decode($response->getContent(), true)
                    : 'No es una respuesta JSON'
            ]);
        }
        // Eliminamos el registro de respuestas exitosas porque ya lo hace LogRequests

        return $response;
    }
}
