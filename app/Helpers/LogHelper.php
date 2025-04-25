<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogHelper
{
    /**
     * Registrar un error específico
     *
     * @param string $message Mensaje de error
     * @param array $context Contexto adicional
     * @param string $channel Canal de log (opcional)
     * @return void
     */
    public static function logError($message, array $context = [], $channel = 'single')
    {
        $baseContext = [
            'user_id' => Auth::id() ?? 'no autenticado',
            'ip' => Request::ip(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString()
        ];
        
        $fullContext = array_merge($baseContext, $context);
        
        Log::channel($channel)->error($message, $fullContext);
    }
    
    /**
     * Registrar información específica
     *
     * @param string $message Mensaje informativo
     * @param array $context Contexto adicional
     * @param string $channel Canal de log (opcional)
     * @return void
     */
    public static function logInfo($message, array $context = [], $channel = 'single')
    {
        $baseContext = [
            'user_id' => Auth::id() ?? 'no autenticado',
            'ip' => Request::ip(),
            'url' => Request::fullUrl(),
            'timestamp' => now()->toDateTimeString()
        ];
        
        $fullContext = array_merge($baseContext, $context);
        
        Log::channel($channel)->info($message, $fullContext);
    }
    
    /**
     * Registrar un error de préstamo
     *
     * @param string $message Mensaje de error
     * @param array $context Contexto adicional
     * @return void
     */
    public static function logPrestamoError($message, array $context = [])
    {
        self::logError($message, $context, 'prestamos');
    }
    
    /**
     * Registrar información de préstamo
     *
     * @param string $message Mensaje informativo
     * @param array $context Contexto adicional
     * @return void
     */
    public static function logPrestamoInfo($message, array $context = [])
    {
        self::logInfo($message, $context, 'prestamos');
    }
    
    /**
     * Registrar una excepción
     *
     * @param \Exception $exception La excepción
     * @param array $context Contexto adicional
     * @param string $channel Canal de log (opcional)
     * @return void
     */
    public static function logException(\Exception $exception, array $context = [], $channel = 'single')
    {
        $exceptionContext = [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
        
        $fullContext = array_merge($exceptionContext, $context);
        
        self::logError('Excepción: ' . $exception->getMessage(), $fullContext, $channel);
    }
} 