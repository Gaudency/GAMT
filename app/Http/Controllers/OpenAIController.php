<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{
    /**
     * Procesa un mensaje del chat y devuelve la respuesta de la IA
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        Log::info('Solicitud recibida en OpenAIController', [
            'mensaje' => $request->message,
            'ip' => $request->ip()
        ]);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Verificar la clave API
        if (empty(config('openai.api_key'))) {
            Log::warning('API key de OpenAI no configurada');
            return response()->json([
                'success' => false,
                'message' => 'El servicio de IA no está configurado correctamente. Por favor contacte al administrador.'
            ], 500);
        }

        Log::info('Clave API configurada', [
            'api_key_length' => strlen(config('openai.api_key')),
            'api_key_first_10' => substr(config('openai.api_key'), 0, 10).'...',
            'base_uri' => config('openai.base_uri')
        ]);

        try {
            Log::info('Enviando solicitud a OpenAI');

            $result = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente útil para el sistema de gestión documental. Proporciona respuestas breves y útiles sobre el manejo de documentos.'],
                    ['role' => 'user', 'content' => $request->message],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            Log::info('Respuesta recibida de OpenAI', [
                'respuesta' => $result->choices[0]->message->content
            ]);

            return response()->json([
                'success' => true,
                'message' => $result->choices[0]->message->content,
            ]);
        } catch (\Exception $e) {
            Log::error('Error en la comunicación con OpenAI', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al comunicarse con el servicio de IA: ' . $e->getMessage(),
            ], 500);
        }
    }
}
