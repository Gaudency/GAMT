<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Telescope\Telescope;

class TelescopeTagCustomRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Alternativa más sencilla
        if (class_exists(Telescope::class)) {
            // Almacenamos las etiquetas apropiadas para esta solicitud
            $tags = [];
            
            if (str_contains($request->path(), 'login') || 
                str_contains($request->path(), 'register') || 
                str_contains($request->path(), 'password')) {
                $tags[] = 'auth';
            }
            
            if (str_contains($request->path(), 'admin')) {
                $tags[] = 'admin';
            }
            
            if (str_contains($request->path(), 'home') || 
                str_contains($request->path(), 'books') || 
                str_contains($request->path(), 'explore')) {
                $tags[] = 'user';
            }
            
            // Si tenemos etiquetas, registrar la solicitud con estas etiquetas
            if (!empty($tags)) {
                // Añadir etiquetas a las entradas actuales y futuras
                Telescope::tag(function () use ($tags) {
                    return $tags;
                });
            }
        }
        
        return $next($request);
    }
} 