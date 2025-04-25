@extends('home.layouts.app')

@section('title', 'Chat con ' . $user->name)

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Agregar la biblioteca RecordRTC -->
<script src="https://www.webrtc-experiment.com/RecordRTC.js"></script>
<style>
@keyframes pulse-slow {
  0%, 100% {
    opacity: 0.8;
  }
  50% {
    opacity: 0.4;
  }
}

@keyframes profile-pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
  }
}

@keyframes message-in {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

@keyframes floating {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.animate-pulse-slow {
  animation: pulse-slow 4s ease-in-out infinite;
}

.animation-delay-1000 {
  animation-delay: 1s;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animate-profile-pulse {
  animation: profile-pulse 2s infinite;
}

.animate-message-in {
  animation: message-in 0.5s ease-out forwards;
}

.animate-fade-in {
  animation: fade-in 1s ease-out forwards;
}

.animate-floating {
  animation: floating 6s ease-in-out infinite;
}

/* Personalización de scroll */
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thumb-white\/20::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 3px;
}

.scrollbar-thumb-white\/20::-webkit-scrollbar-thumb:hover {
  background-color: rgba(255, 255, 255, 0.4);
}

.scrollbar-track-transparent::-webkit-scrollbar-track {
  background-color: transparent;
}

/* Personalización del reproductor de audio */
.audio-player {
  filter: hue-rotate(215deg);
  border-radius: 20px;
  background-color: rgba(255, 255, 255, 0.1);
}

.audio-player::-webkit-media-controls-panel {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Marca de agua */
.watermark {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 50%;
  height: auto;
  opacity: 0.08;
  pointer-events: none;
  z-index: 1;
}
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-600 via-purple-700 to-violet-800 relative overflow-hidden">
    <!-- Elementos decorativos de fondo -->
    <div class="absolute w-[500px] h-[500px] rounded-full bg-red-500/30 blur-3xl -top-40 -right-40 animate-pulse-slow"></div>
    <div class="absolute w-[600px] h-[600px] rounded-full bg-violet-600/20 blur-3xl bottom-0 -left-32 animate-pulse-slow animation-delay-2000"></div>
    <div class="absolute w-[400px] h-[400px] rounded-full bg-pink-600/25 blur-3xl top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 animate-pulse-slow animation-delay-1000"></div>

    <div class="relative container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto relative z-10">
             <!-- Marca de agua con el escudo -->
             <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none z-[1]">
                        <img src="{{ asset('images/loguito.png') }}" alt="Escudo" class="w-[900px] opacity-20">
                    </div>
            <!-- Botones de navegación y tema -->
            <div class="fixed top-4 right-4 flex items-center space-x-3 z-20">
                <!-- Zona para botones adicionales -->
            </div>

            <!-- Encabezado del chat -->
            <div class="backdrop-blur-xl bg-white/10 rounded-t-2xl shadow-2xl p-4 border border-white/20 transition-all duration-300 hover:bg-white/15">
            <div class="flex items-center space-x-4">

                    @if(auth()->user()->admin == 1)
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-white/80 transition-all duration-300 transform hover:scale-110">
                    @else
                        <a href="{{ route('home') }}" class="text-white hover:text-white/80 transition-all duration-300 transform hover:scale-110">
                    @endif
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="relative">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full ring-2 ring-white/30 hover:ring-white/80 transition-all duration-300 animate-profile-pulse">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white drop-shadow-md transition-all duration-300">
                            {{ $user->name }}
                        </h2>
                        <p class="text-white/70 text-sm transition-all duration-300">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="message-container flex-1 overflow-y-auto p-4" id="message-container">
                <!-- Mensajes -->
                <div id="messages" class="backdrop-blur-md bg-white/5 h-[550px] overflow-y-auto p-4 space-y-4 transition-all duration-300 border-x border-white/20 scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent relative">

                    @if(isset($borrow) && $borrow)
                    <div class="mb-6 bg-blue-600/20 backdrop-blur-lg p-4 rounded-xl border border-blue-400/30 transform hover:scale-[1.01] transition-all duration-300 animate-fade-in relative z-10">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-400 mt-1 mr-3 text-lg"></i>
                            <div>
                                <h3 class="font-medium text-white mb-1">Consulta sobre préstamo #{{ $borrow->id }}</h3>
                                <p class="text-white/80 text-sm">
                                    @if($borrow->book)
                                        Este chat está relacionado con tu solicitud para la carpeta "{{ $borrow->book->title }}".
                                    @else
                                        Este chat está relacionado con una solicitud de préstamo.
                                    @endif
                                </p>
                                <p class="text-white/70 text-xs mt-2">
                                    Estado:
                                    @if($borrow->status == 'Applied')
                                        <span class="px-2 py-0.5 bg-yellow-500/20 text-white rounded-full">Pendiente</span>
                                    @elseif($borrow->status == 'Approved')
                                        <span class="px-2 py-0.5 bg-green-500/20 text-white rounded-full">Aprobado</span>
                                    @elseif($borrow->status == 'Rejected')
                                        <span class="px-2 py-0.5 bg-red-500/20 text-white rounded-full">Rechazado</span>
                                    @elseif($borrow->status == 'Returned')
                                        <span class="px-2 py-0.5 bg-blue-500/20 text-white rounded-full">Devuelto</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-500/20 text-white rounded-full">{{ $borrow->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} animate-message-in relative z-10" style="animation-delay: {{ $loop->index * 0.05 }}s">
                            <div class="max-w-[70%] {{ $message->sender_id === auth()->id() ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white' : 'bg-white/10 backdrop-blur-xl text-white' }} rounded-xl p-3 border border-white/20 shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                                @if($message->message)
                                    <p class="text-sm break-words">
                                        {{ $message->message }}
                                    </p>
                                @endif

                                @if($message->attachments->count() > 0)
                                    <div class="mt-2 space-y-2">
                                        @foreach($message->attachments as $attachment)
                                            <div class="flex items-center space-x-2 p-2 rounded bg-black/10 backdrop-blur-lg hover:bg-black/15 transition-all duration-300 border border-white/10">
                                                @if($attachment->is_audio)
                                                    <!-- Reproductor de audio -->
                                                    <div class="w-full">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <i class="fas fa-headphones text-blue-300 animate-pulse"></i>
                                                            <span class="text-sm text-white/90">Mensaje de voz</span>
                                                        </div>
                                                        <audio src="{{ route('chat.download', $attachment) }}"
                                                            controls
                                                            class="w-full max-w-full audio-player">
                                                            Tu navegador no soporta la reproducción de audio.
                                                        </audio>
                                                    </div>
                                                @elseif(Str::startsWith($attachment->file_type, 'image/'))
                                                    <div class="relative group transition-all duration-300 transform hover:scale-[1.03]">
                                                        <a href="{{ route('chat.download', $attachment) }}" target="_blank" title="Ver imagen completa">
                                                            <img src="{{ route('chat.download', $attachment) }}"
                                                                alt="Imagen adjunta"
                                                                class="max-w-xs rounded cursor-pointer hover:opacity-90 transition-all duration-300">
                                                        </a>
                                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                            <a href="{{ route('chat.download', $attachment) }}?download=true"
                                                            class="bg-black/50 hover:bg-black/70 text-white rounded-full p-2 flex items-center justify-center transform hover:scale-110 transition-all duration-300"
                                                            title="Descargar imagen">
                                                                <i class="fas fa-download text-sm"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="{{ route('chat.download', $attachment) }}"
                                                    class="flex items-center space-x-2 text-sm hover:underline text-white/90 hover:text-white transition-all duration-300 transform hover:translate-x-1">
                                                        @php
                                                            $fileIcon = 'fa-file';
                                                            if (Str::contains($attachment->file_type, 'pdf')) $fileIcon = 'fa-file-pdf';
                                                            elseif (Str::contains($attachment->file_type, ['word', 'doc'])) $fileIcon = 'fa-file-word';
                                                            elseif (Str::contains($attachment->file_type, ['excel', 'spreadsheet', 'xls'])) $fileIcon = 'fa-file-excel';
                                                            elseif (Str::contains($attachment->file_type, ['zip', 'rar', 'archive', '7z'])) $fileIcon = 'fa-file-archive';
                                                            elseif (Str::contains($attachment->file_type, ['powerpoint', 'presentation', 'ppt'])) $fileIcon = 'fa-file-powerpoint';
                                                            elseif (Str::contains($attachment->file_type, 'text')) $fileIcon = 'fa-file-alt';
                                                        @endphp
                                                        <i class="fas {{ $fileIcon }} text-lg"></i>
                                                        <span>{{ $attachment->file_name }}</span>
                                                        <span class="text-xs opacity-75">({{ formatBytes($attachment->file_size) }})</span>
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-1 flex items-center justify-end space-x-2">
                                    <span class="text-xs opacity-75">
                                        {{ $message->created_at->format('H:i') }}
                                    </span>
                                    @if($message->sender_id === auth()->id())
                                        <span class="text-xs">
                                            @if($message->is_read)
                                                <i class="fas fa-check-double"></i>
                                            @else
                                                <i class="fas fa-check"></i>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Formulario de envío -->
            <div class="backdrop-blur-xl bg-white/10 rounded-b-2xl shadow-2xl p-4 border-t border-white/20 transition-all duration-300">
                <form id="messageForm" class="space-y-4" method="POST" action="{{ route('chat.send') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    @if(isset($borrow_id) && $borrow_id)
                        <input type="hidden" name="borrow_id" value="{{ $borrow_id }}">
                    @endif

                    <div class="relative">
                        <textarea id="message" name="message"
                                rows="3"
                                class="block w-full rounded-xl border-white/20 bg-white/5 backdrop-blur-lg text-white placeholder-white/60 focus:border-pink-500 focus:ring-pink-500 transition-all duration-300 resize-none"
                                placeholder="Escribe tu mensaje..."></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Botón de archivos -->
                            <label class="cursor-pointer text-white/80 hover:text-white transition-all duration-300 transform hover:scale-110">
                                <input id="attachments" type="file"
                                    name="attachments[]"
                                    multiple
                                    class="hidden"
                                    accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.7z,.ppt,.pptx,.txt">
                                <i class="fas fa-paperclip text-xl"></i>
                            </label>

                            <!-- Botón para grabación de audio -->
                            <button type="button" id="recordButton" class="text-white/80 hover:text-white transition-all duration-300 transform hover:scale-110">
                                <i class="fas fa-microphone text-xl"></i>
                            </button>

                            <!-- Indicador de grabación -->
                            <div id="recordingIndicator" class="hidden items-center space-x-2 text-red-300">
                                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                <span class="text-sm">Grabando...</span>
                                <span id="recordingTime" class="text-xs font-mono">00:00</span>
                            </div>

                            <div id="filePreview" class="text-sm text-white/80 transition-all duration-300"></div>
                            <div id="audioPreview" class="hidden text-sm text-white/80 transition-all duration-300"></div>
                        </div>

                        <button type="submit"
                                class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-violet-500 hover:from-red-600 hover:to-violet-600 text-white rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-1 transition-all duration-300">
                            Enviar
                            <i class="fas fa-paper-plane ml-1"></i>
                        </button>
                    </div>

                    <!-- Campo oculto para el archivo de audio -->
                    <input type="hidden" name="audio_message" id="audio_message">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Definir la función toggleDarkMode globalmente
function toggleDarkMode() {
    console.log('toggleDarkMode llamada en chat/show');
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
        console.log('Modo oscuro desactivado');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
        console.log('Modo oscuro activado');
    }
}

// Asegurarnos que el modo oscuro se aplica correctamente al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado en chat/show, verificando modo oscuro...');
    if (localStorage.getItem('darkMode') === 'true' ||
        (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        console.log('Modo oscuro aplicado al cargar');
    } else {
        document.documentElement.classList.remove('dark');
        console.log('Modo claro aplicado al cargar');
    }

    // Variables para audio
    let recorder;
    let isRecording = false;
    let recordingTimer;
    let recordingSeconds = 0;
    let audioBlob;

    // Función para formatear el tiempo de grabación
    function formatRecordingTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        seconds = seconds % 60;
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    // Configuración del botón de grabación
    const recordButton = document.getElementById('recordButton');
    if (recordButton) {
        console.log('✅ Botón de grabación encontrado');
        recordButton.onclick = function() {
            console.log('🔴 Botón de grabación presionado (vía onclick directo)');
            if (isRecording) {
                stopRecording();
            } else {
                startRecording();
            }
            return false;
        };
        console.log('✅ Evento onclick asignado al botón de grabación.');
    } else {
        console.error('❌ No se encontró botón de grabación');
    }

    // Iniciar grabación con RecordRTC
    function startRecording() {
        console.log('▶️ Iniciando grabación...');

        // Verificar soporte
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Tu navegador no soporta grabación de audio');
            console.error('❌ API MediaDevices no soportada');
            return;
        }

        // Actualizar UI antes de solicitar permisos
        const recordButton = document.getElementById('recordButton');
        const recordingIndicator = document.getElementById('recordingIndicator');
        const recordingTime = document.getElementById('recordingTime');

        // Solicitar permisos de micrófono
        navigator.mediaDevices.getUserMedia({audio: true})
            .then(function(stream) {
                console.log('✅ Permiso de micrófono concedido');

                // Crear instancia de RecordRTC
                recorder = RecordRTC(stream, {
                    type: 'audio',
                    mimeType: 'audio/webm',
                    sampleRate: 44100,
                    desiredSampleRate: 16000,
                    recorderType: RecordRTC.StereoAudioRecorder,
                    numberOfAudioChannels: 1
                });

                // Iniciar grabación
                recorder.startRecording();
                isRecording = true;

                // Actualizar UI
                recordButton.innerHTML = '<i class="fas fa-stop-circle text-xl text-red-500"></i>';
                recordingIndicator.classList.remove('hidden');
                recordingIndicator.classList.add('flex');

                // Iniciar contador
                recordingSeconds = 0;
                recordingTime.textContent = '00:00';
                recordingTimer = setInterval(function() {
                    recordingSeconds++;
                    recordingTime.textContent = formatRecordingTime(recordingSeconds);
                    if (recordingSeconds >= 120) { // Límite 2 minutos
                        stopRecording();
                    }
                }, 1000);

                // Guardar stream para detenerlo después
                recorder.stream = stream;

                console.log('✅ Grabación iniciada correctamente');
            })
            .catch(function(error) {
                console.error('❌ Error al obtener permiso de micrófono:', error.name, error.message);
                alert('Error de micrófono: ' + error.message);
            });
    }

    // Detener grabación
    function stopRecording() {
        console.log('⏹️ Deteniendo grabación...');

        if (!isRecording || !recorder) {
            console.warn('⚠️ No hay grabación activa');
            return;
        }

        // Actualizar estado
        isRecording = false;

        // Detener temporizador
        clearInterval(recordingTimer);

        // Actualizar UI
        const recordButton = document.getElementById('recordButton');
        const recordingIndicator = document.getElementById('recordingIndicator');

        recordButton.innerHTML = '<i class="fas fa-microphone text-xl"></i>';
        recordingIndicator.classList.add('hidden');
        recordingIndicator.classList.remove('flex');

        // Detener grabación y procesar audio
        recorder.stopRecording(function() {
            console.log('✅ Grabación detenida');

            // Obtener blob de audio
            audioBlob = recorder.getBlob();
            console.log('✅ Audio blob creado:', audioBlob.size, 'bytes');

            // Crear URL para reproducción
            const audioURL = URL.createObjectURL(audioBlob);

            // Mostrar reproductor
            const audioPreview = document.getElementById('audioPreview');
            audioPreview.innerHTML = `
                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-headphones text-blue-500"></i>
                            <span>Mensaje de voz (${formatRecordingTime(recordingSeconds)})</span>
                        </div>
                        <button type="button" id="deleteAudio" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <audio src="${audioURL}" controls class="w-full mt-2"></audio>
                </div>
            `;
            audioPreview.classList.remove('hidden');

            // Agregar evento para eliminar
            document.getElementById('deleteAudio').addEventListener('click', function() {
                audioPreview.innerHTML = '';
                audioPreview.classList.add('hidden');
                document.getElementById('audio_message').value = '';
            });

            // Convertir a base64 para enviar
            const fileReader = new FileReader();
            fileReader.onloadend = function() {
                document.getElementById('audio_message').value = fileReader.result;
                console.log('✅ Audio convertido a base64');
            };
            fileReader.readAsDataURL(audioBlob);

            // Detener pistas del micrófono
            recorder.stream.getTracks().forEach(track => track.stop());
        });
    }

    // Definir variables globales con datos de PHP
    const AUTH_USER_ID = parseInt('{{ auth()->id() }}') || 0;

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function scrollToBottom() {
        const messagesContainer = document.getElementById('messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function createMessageHtml(message) {
        console.log('Creando HTML para mensaje:', message);
        const isCurrentUser = message.sender_id === AUTH_USER_ID;
        const time = new Date(message.created_at).toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit'
        });

        let attachmentsHtml = '';
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = '<div class="mt-2 space-y-2">';
            message.attachments.forEach(attachment => {
                if (attachment.is_audio) {
                    // Mostrar reproductor de audio
                    attachmentsHtml += `
                        <div class="flex items-center space-x-2 p-2 rounded bg-black/10 dark:bg-black/20">
                            <div class="w-full">
                                <div class="flex items-center space-x-2 mb-1">
                                    <i class="fas fa-headphones text-blue-400"></i>
                                    <span class="text-sm">Mensaje de voz</span>
                                </div>
                                <audio src="/chat/attachment/${attachment.id}/download"
                                       controls
                                       class="w-full max-w-full">
                                    Tu navegador no soporta la reproducción de audio.
                                </audio>
                            </div>
                        </div>`;
                }
                else if (attachment.file_type && attachment.file_type.startsWith('image/')) {
                    attachmentsHtml += `
                        <div class="flex items-center space-x-2 p-2 rounded bg-black/10 dark:bg-black/20">
                            <div class="relative group">
                                <a href="/chat/attachment/${attachment.id}/download" target="_blank" title="Ver imagen completa">
                                    <img src="/chat/attachment/${attachment.id}/download"
                                         alt="Imagen adjunta"
                                         class="max-w-xs rounded cursor-pointer hover:opacity-90 transition-opacity">
                                </a>
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="/chat/attachment/${attachment.id}/download?download=true"
                                       class="bg-gray-800/70 hover:bg-gray-900/90 text-white rounded-full p-2 flex items-center justify-center"
                                       title="Descargar imagen">
                                        <i class="fas fa-download text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>`;
                } else {
                    // Identificar tipo de archivo para mostrar icono apropiado
                    let fileIcon = 'fa-file';
                    if (attachment.file_type) {
                        if (attachment.file_type.includes('pdf')) fileIcon = 'fa-file-pdf';
                        else if (attachment.file_type.includes('word') || attachment.file_type.includes('doc')) fileIcon = 'fa-file-word';
                        else if (attachment.file_type.includes('excel') || attachment.file_type.includes('spreadsheet') || attachment.file_type.includes('xls')) fileIcon = 'fa-file-excel';
                        else if (attachment.file_type.includes('zip') || attachment.file_type.includes('rar') || attachment.file_type.includes('archive')) fileIcon = 'fa-file-archive';
                        else if (attachment.file_type.includes('powerpoint') || attachment.file_type.includes('presentation') || attachment.file_type.includes('ppt')) fileIcon = 'fa-file-powerpoint';
                        else if (attachment.file_type.includes('text')) fileIcon = 'fa-file-alt';
                    }

                    attachmentsHtml += `
                        <div class="flex items-center space-x-2 p-2 rounded bg-black/10 dark:bg-black/20">
                            <a href="/chat/attachment/${attachment.id}/download"
                               class="flex items-center space-x-2 text-sm hover:underline"
                               target="_blank">
                                <i class="fas ${fileIcon} text-lg"></i>
                                <span>${attachment.file_name}</span>
                                <span class="text-xs opacity-75">(${formatBytes(attachment.file_size)})</span>
                            </a>
                        </div>`;
                }
            });
            attachmentsHtml += '</div>';
        }

        return `
            <div class="flex ${isCurrentUser ? 'justify-end' : 'justify-start'}">
                <div class="max-w-[70%] ${isCurrentUser ? 'bg-gradient-to-r from-red-500 to-pink-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white'} rounded-lg shadow-md p-3">
                    ${message.message ? `<p class="text-sm break-words">${message.message}</p>` : ''}
                    ${attachmentsHtml}
                    <div class="mt-1 flex items-center justify-end space-x-2">
                        <span class="text-xs opacity-75">${time}</span>
                        ${isCurrentUser ? `
                            <span class="text-xs">
                                <i class="fas fa-check"></i>
                            </span>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    function sendMessage(e) {
        e.preventDefault();
        console.log('Iniciando función sendMessage');

        const form = document.getElementById('messageForm');
        if (!form) {
            console.error('No se encontró el formulario de mensajes');
            return;
        }

        const formData = new FormData(form);
        console.log('FormData creado');

        // Verificar contenido del FormData
        for (let [key, value] of formData.entries()) {
            if (key === 'audio_message') {
                console.log('FormData contiene audio_message:', value ? `${value.substring(0, 30)}...` : 'vacío');
            } else if (key === 'attachments[]') {
                console.log('FormData contiene attachments:', value.name, value.type, value.size);
            } else {
                console.log('FormData contiene:', key, value);
            }
        }

        // Verificar si hay un mensaje o archivos o audio
        const message = formData.get('message') ? formData.get('message').trim() : '';
        const files = document.getElementById('attachments').files;
        const audioMessage = formData.get('audio_message');

        console.log('Verificando contenido:');
        console.log('- Mensaje de texto:', message ? 'presente' : 'ausente');
        console.log('- Archivos adjuntos:', files.length);
        console.log('- Mensaje de audio:', audioMessage ? 'presente' : 'ausente');

        if (!message && files.length === 0 && !audioMessage) {
            console.warn('Intento de envío sin contenido');
            alert('Por favor, escribe un mensaje, adjunta un archivo o graba un mensaje de voz');
            return;
        }

        // Verificar token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('No se encontró el token CSRF');
            alert('Error de seguridad: Token CSRF no encontrado');
            return;
        }

        console.log('Enviando solicitud AJAX:', form.action);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            console.log('Respuesta del servidor recibida:', response.status);
            if (!response.ok) {
                console.error('Error en la respuesta:', response.status, response.statusText);
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            if (data.success) {
                // Agregar el nuevo mensaje al chat
                console.log('Creando HTML para el nuevo mensaje');
                const messageHtml = createMessageHtml(data.data);
                document.getElementById('messages').innerHTML += messageHtml;

                // Limpiar el formulario
                form.reset();
                document.getElementById('filePreview').innerHTML = '';
                document.getElementById('audioPreview').classList.add('hidden');
                document.getElementById('audioPreview').innerHTML = '';
                document.getElementById('audio_message').value = '';

                // Scroll al final
                scrollToBottom();
                console.log('Mensaje enviado y mostrado exitosamente');
            } else {
                console.error('Error en respuesta:', data.message);
                alert('Error al enviar el mensaje: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            alert('Error al enviar el mensaje: ' + error.message);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM cargado, iniciando configuración del chat...');

        const messagesContainer = document.getElementById('messages');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('message');
        const fileInput = messageForm ? messageForm.querySelector('input[type="file"]') : null;
        const filePreview = document.getElementById('filePreview');
        const submitButton = messageForm ? messageForm.querySelector('button[type="submit"]') : null;

        if (!messagesContainer || !messageForm || !messageInput || !fileInput || !filePreview || !submitButton) {
            console.error('No se encontraron todos los elementos necesarios del chat');
            return;
        }

        // Scroll al último mensaje
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        console.log('Scroll aplicado al contenedor de mensajes');

        // Enviar mensaje con Enter (excepto Shift+Enter para nueva línea)
       // messageInput.addEventListener('keydown', function(e) {
        //    if (e.key === 'Enter' && !e.shiftKey) {
        //        console.log('Tecla Enter presionada, enviando mensaje...');
        //        e.preventDefault();
        //        sendMessage(new Event('submit'));
        //    }
       //  });

        // Previsualización de archivos
        fileInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            console.log('Archivos seleccionados:', files.length);

            if (files.length > 0) {
                let previewHTML = '<div class="mt-3 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">';
                previewHTML += `<div class="text-xs font-medium mb-2">${files.length} archivo(s) seleccionado(s)
                                 <button type="button" id="clearAllFiles" class="text-red-500 hover:text-red-700 text-xs ml-2">
                                     Eliminar todos
                                 </button>
                               </div>`;

                files.forEach((file, index) => {
                    console.log(`Archivo ${index}: ${file.name}, tipo: ${file.type}, tamaño: ${file.size}`);
                    let fileIcon = 'fa-file';

                    // Determinar icono según tipo de archivo
                    if (file.type.includes('pdf')) fileIcon = 'fa-file-pdf text-red-500';
                    else if (file.type.includes('word') || file.type.includes('doc')) fileIcon = 'fa-file-word text-blue-600';
                    else if (file.type.includes('excel') || file.type.includes('spreadsheet') || file.type.includes('sheet')) fileIcon = 'fa-file-excel text-green-600';
                    else if (file.type.includes('zip') || file.type.includes('rar') || file.type.includes('archive')) fileIcon = 'fa-file-archive text-yellow-600';
                    else if (file.type.includes('powerpoint') || file.type.includes('presentation')) fileIcon = 'fa-file-powerpoint text-orange-500';
                    else if (file.type.includes('text')) fileIcon = 'fa-file-alt text-gray-600';

                    if (file.type.startsWith('image/')) {
                        // Crear URL de objeto para vista previa de imagen
                        const imageUrl = URL.createObjectURL(file);
                        console.log(`Imagen URL creada: ${imageUrl}`);
                        previewHTML += `
                            <div class="flex items-center space-x-3 py-2 border-t border-gray-200 dark:border-gray-600 relative file-preview-item" data-index="${index}">
                                <img src="${imageUrl}" alt="Vista previa" class="h-12 w-12 object-cover rounded border border-gray-300 dark:border-gray-500">
                                <div class="flex-1">
                                    <div class="text-xs font-medium truncate">${file.name}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">${formatBytes(file.size)}</div>
                                </div>
                                <button type="button" class="remove-file text-red-500 hover:text-red-700 absolute top-2 right-0">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                    } else {
                        // Mostrar icono para otros tipos de archivo
                        previewHTML += `
                            <div class="flex items-center space-x-3 py-2 border-t border-gray-200 dark:border-gray-600 relative file-preview-item" data-index="${index}">
                                <div class="h-12 w-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-500">
                                    <i class="fas ${fileIcon} text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-medium truncate">${file.name}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">${formatBytes(file.size)}</div>
                                </div>
                                <button type="button" class="remove-file text-red-500 hover:text-red-700 absolute top-2 right-0">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                    }
                });

                previewHTML += '</div>';
                filePreview.innerHTML = previewHTML;
                console.log('Vista previa de archivos generada');

                // Agregar event listeners para eliminar archivos individualmente
                document.querySelectorAll('.remove-file').forEach(button => {
                    button.addEventListener('click', function() {
                        const item = this.closest('.file-preview-item');
                        const index = parseInt(item.dataset.index);
                        console.log(`Eliminando archivo en índice ${index}`);

                        // Crear una nueva lista de archivos excluyendo el eliminado
                        const dt = new DataTransfer();
                        for (let i = 0; i < fileInput.files.length; i++) {
                            if (i !== index) {
                                dt.items.add(fileInput.files[i]);
                                console.log(`Manteniendo archivo ${i}: ${fileInput.files[i].name}`);
                            }
                        }
                        fileInput.files = dt.files;
                        console.log(`Quedan ${fileInput.files.length} archivos`);

                        // Actualizar la vista previa
                        if (fileInput.files.length > 0) {
                            // Disparar el evento change para actualizar la vista previa
                            fileInput.dispatchEvent(new Event('change'));
                        } else {
                            filePreview.innerHTML = '';
                        }
                    });
                });

                // Agregar event listener para eliminar todos los archivos
                document.getElementById('clearAllFiles').addEventListener('click', function() {
                    console.log('Eliminando todos los archivos');
                    fileInput.value = '';
                    filePreview.innerHTML = '';
                });

                // Liberar URLs de objeto cuando se limpie la previsualización
                window.setTimeout(() => {
                    files.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            URL.revokeObjectURL(file);
                        }
                    });
                }, 0);
            } else {
                filePreview.innerHTML = '';
            }
        });

        // Envío del formulario
        messageForm.addEventListener('submit', function(e) {
            console.log('Formulario enviado, procesando...');
            sendMessage(e);
        });

        // Comprobar si hay symlink para storage
        fetch('/storage/test')
            .then(response => {
                console.log('Prueba de storage completada:', response.status);
                if (!response.ok) {
                    console.warn('Posible problema con storage:link. Las imágenes podrían no mostrarse correctamente.');
                }
            })
            .catch(error => {
                console.warn('Problema al acceder a /storage:', error);
            });

        console.log('Configuración del chat completada');
    });

    // Asegurar que siempre hacemos scroll al último mensaje
    scrollToBottom();

    // Evitar el envío tradicional del formulario
    const messageForm = document.getElementById('messageForm');
    if (messageForm) {
        // Sobrescribir el evento submit para usar solo AJAX
        messageForm.onsubmit = function(e) {
            e.preventDefault();
            sendMessage(e);
            return false;
        };
    }

    // Asegurar que cualquier recarga de página también lleva al final
    window.addEventListener('load', function() {
        setTimeout(scrollToBottom, 500); // Pequeño retraso para asegurar carga completa
    });
});
</script>
@endpush
