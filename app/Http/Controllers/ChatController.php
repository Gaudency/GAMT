<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatAttachment;
use App\Models\User;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Obtener todas las conversaciones del usuario
        $conversations = ChatMessage::where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($user) {
                return $message->sender_id == $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function($messages) {
                return [
                    'last_message' => $messages->first(),
                    'unread_count' => $messages->where('is_read', false)->count(),
                ];
            });

        // Obtener la lista de administradores
        $admins = User::where('usertype', 'admin')->get();

        return view('chat.index', compact('conversations', 'admins'));
    }

    /**
     * Muestra la conversación con un usuario específico.
     *
     * @param  int|App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        try {
            // Si es un ID, buscar el usuario por ID
            if (is_numeric($user)) {
                $user = User::findOrFail($user);
            }

            // Marcar los mensajes como leídos
            ChatMessage::where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

            // Obtener los mensajes entre el usuario actual y el usuario seleccionado
            $messages = ChatMessage::where(function($query) use ($user) {
                            $query->where('sender_id', auth()->id())
                                  ->where('receiver_id', $user->id);
                        })
                        ->orWhere(function($query) use ($user) {
                            $query->where('sender_id', $user->id)
                                  ->where('receiver_id', auth()->id());
                        })
                        ->with(['sender', 'attachments'])
                        ->orderBy('created_at', 'asc')
                        ->get();

            // Obtener el ID del préstamo si existe en la URL
            $borrow_id = request()->query('borrow_id');
            $borrow = null;

            // Buscar el préstamo solo si el ID está presente
            if ($borrow_id) {
                try {
                    $borrow = Borrow::find($borrow_id);

                    // Si el préstamo no existe, loguear pero continuar sin errores
                    if (!$borrow) {
                        Log::warning('Préstamo no encontrado', ['borrow_id' => $borrow_id]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error al buscar préstamo: ' . $e->getMessage(), [
                        'borrow_id' => $borrow_id
                    ]);
                    // Continuar sin el préstamo
                }
            }

            return view('chat.show', [
                'user' => $user,
                'messages' => $messages,
                'borrow_id' => $borrow_id,
                'borrow' => $borrow
            ]);
        } catch (\Exception $e) {
            Log::error('Error en ChatController@show: ' . $e->getMessage());
            return redirect()->route('chat.index')->with('error', 'Hubo un problema al cargar la conversación');
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            Log::info('Intentando enviar mensaje', $request->all());

            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'message' => 'nullable|string',
                'attachments.*' => 'nullable|file|max:10240', // 10MB máximo
                'borrow_id' => 'nullable|exists:borrows,id',
                'audio_message' => 'nullable|string' // Para mensajes de audio en base64
            ]);

            // Si no hay mensaje, audio ni archivos, devolver error
            if (empty($request->message) && !$request->hasFile('attachments') && empty($request->audio_message)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar un mensaje, audio o un archivo adjunto'
                ], 422);
            }

            $message = new ChatMessage();
            $message->sender_id = auth()->id();
            $message->receiver_id = $request->receiver_id;
            $message->message = $request->message ?: ''; // Usar cadena vacía en lugar de null
            $message->borrow_id = $request->borrow_id;
            $message->save();

            Log::info('Mensaje guardado', ['message_id' => $message->id]);

            // Procesar mensaje de audio (si existe)
            if (!empty($request->audio_message)) {
                // Convertir de base64 a archivo
                $audioData = $request->audio_message;
                // Eliminar el encabezado data:audio/webm;base64, para obtener solo los datos
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
                $audioData = base64_decode($audioData);

                // Generar nombre único para el archivo
                $fileName = 'audio_' . time() . '_' . Str::random(10) . '.webm';
                $path = 'chat_attachments/' . $fileName;

                // Guardar archivo en disco
                Storage::disk('public')->put($path, $audioData);

                // Crear adjunto para el mensaje
                $attachment = new ChatAttachment();
                $attachment->chat_message_id = $message->id;
                $attachment->file_name = 'Mensaje de voz.webm';
                $attachment->file_path = $path;
                $attachment->file_type = 'audio/webm';
                $attachment->file_size = strlen($audioData);
                $attachment->is_audio = true; // Marcar como audio
                $attachment->save();

                Log::info('Audio guardado', [
                    'attachment_id' => $attachment->id,
                    'path' => $path,
                    'exists' => Storage::disk('public')->exists($path)
                ]);
            }

            // Procesar archivos adjuntos
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    // Almacenar en 'public' y obtener la ruta relativa
                    $path = $file->store('chat_attachments', 'public');

                    $attachment = new ChatAttachment();
                    $attachment->chat_message_id = $message->id;
                    $attachment->file_name = $file->getClientOriginalName();
                    $attachment->file_path = $path;
                    $attachment->file_type = $file->getMimeType();
                    $attachment->file_size = $file->getSize();
                    $attachment->is_audio = false; // No es audio
                    $attachment->save();

                    Log::info('Archivo adjunto guardado', [
                        'attachment_id' => $attachment->id,
                        'path' => $path,
                        'exists' => Storage::disk('public')->exists($path)
                    ]);
                }
            }

            // Cargar relaciones para la respuesta
            $message->load(['sender', 'receiver', 'attachments']);

            Log::info('Enviando respuesta JSON', [
                'message_id' => $message->id,
                'has_sender' => isset($message->sender),
                'has_receiver' => isset($message->receiver),
                'attachments_count' => $message->attachments->count()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mensaje enviado correctamente',
                    'data' => $message
                ]);
            }

            return redirect()->back()->with('success', 'Mensaje enviado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al enviar el mensaje: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al enviar el mensaje: ' . $e->getMessage());
        }
    }

    public function getUnreadCount()
    {
        $count = ChatMessage::where('receiver_id', Auth::id())
                          ->where('is_read', false)
                          ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead(ChatMessage $message)
    {
        if ($message->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $message->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function downloadAttachment(ChatAttachment $attachment)
    {
        // Verificar que el usuario tenga acceso al archivo
        $message = $attachment->message;
        if (!$message || ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id())) {
            abort(403);
        }

        // Verificar que el archivo exista
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            Log::error('Archivo no encontrado', [
                'path' => $attachment->file_path
            ]);
            abort(404, 'Archivo no encontrado');
        }

        $filePath = storage_path('app/public/' . $attachment->file_path);
        $forceDownload = request()->has('download');

        // Si es una imagen y no se solicita descarga, mostrarla en línea
        if (Str::startsWith($attachment->file_type, 'image/') && !$forceDownload) {
            return response()->file($filePath);
        }

        // Para otros tipos o si se solicita descarga explícita, forzar descarga
        return response()->download($filePath, $attachment->file_name, [
            'Content-Type' => $attachment->file_type
        ]);
    }

    public function deleteMessage(ChatMessage $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Eliminar archivos adjuntos
        foreach ($message->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $message->delete();
        return response()->json(['success' => true]);
    }
}
