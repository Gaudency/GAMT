<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class PrestamoException extends Exception
{
    protected $context;
    
    /**
     * Constructor con contexto adicional
     *
     * @param string $message
     * @param array $context
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, array $context = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        
        // Registrar automáticamente la excepción
        $this->report();
    }
    
    /**
     * Registrar la excepción en el log
     */
    public function report()
    {
        Log::channel('prestamos')->error('Error de préstamo: ' . $this->getMessage(), array_merge([
            'exception' => get_class($this),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
        ], $this->context));
    }
    
    /**
     * Renderizar la excepción como respuesta HTTP
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'error_code' => $this->getCode()
            ], 500);
        }
        
        return back()->with('error', $this->getMessage());
    }
}
