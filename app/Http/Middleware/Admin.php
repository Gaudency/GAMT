<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\TokenMismatchException;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Verificar si el usuario está autenticado
            if (!Auth::check()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
                        'redirect' => route('login')
                    ], 401);
                }
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
            }

            // Verificar si el usuario es admin
            if (Auth::user()->usertype !== 'admin') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permisos para realizar esta acción.',
                        'redirect' => route('home')
                    ], 403);
                }
                return redirect()->route('home')
                    ->with('error', 'No tienes acceso a esta página.');
            }

            // Verificar si la sesión está activa
            if (!$request->session()->isStarted()) {
                $request->session()->start();
            }

            // Regenerar el ID de sesión periódicamente para prevenir ataques
            if ($request->session()->get('last_activity', 0) < time() - 300) { // 5 minutos
                $request->session()->regenerate();
                $request->session()->put('last_activity', time());
            }

            return $next($request);

        } catch (TokenMismatchException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de seguridad expirado. Por favor, recarga la página.',
                    'redirect' => route('login')
                ], 419);
            }
            return redirect()->route('login')
                ->with('error', 'Token de seguridad expirado. Por favor, inicia sesión nuevamente.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ha ocurrido un error inesperado.',
                    'redirect' => route('home')
                ], 500);
            }
            return redirect()->route('home')
                ->with('error', 'Ha ocurrido un error inesperado.');
        }
    }
}
