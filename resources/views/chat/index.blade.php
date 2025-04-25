@extends('home.layouts.app')

@section('title', 'Mis Conversaciones')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-800 to-violet-900 relative overflow-hidden">
    <!-- Elementos decorativos de fondo -->
    <div class="absolute w-64 h-64 rounded-full bg-purple-500/20 blur-3xl -top-10 -right-10"></div>
    <div class="absolute w-96 h-96 rounded-full bg-violet-500/20 blur-3xl bottom-0 -left-20"></div>
    <div class="absolute w-80 h-80 rounded-full bg-pink-500/20 blur-3xl top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>

    <div class="relative container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto relative z-10">
            <div class="flex items-center mb-8">
                @if(auth()->user()->admin == 1)
                    <a href="{{ route('admin.dashboard') }}" class="mr-4 text-white hover:text-white/80 transition-colors duration-300">
                @else
                    <a href="{{ route('home') }}" class="mr-4 text-white hover:text-white/80 transition-colors duration-300">
                @endif
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-white drop-shadow-md">Mis Conversaciones</h1>
            </div>

            @if($conversations->isEmpty())
                <div class="backdrop-blur-xl bg-white/10 rounded-3xl shadow-2xl p-8 border border-white/20">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6 backdrop-blur-sm shadow-inner">
                            <i class="fas fa-comments text-3xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-semibold text-white mb-3 drop-shadow-sm">No tienes conversaciones</h2>
                        <p class="text-white/80 text-lg">Inicia una conversación con un administrador</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($admins as $admin)
                            <div class="flex items-center space-x-4 p-5 bg-white/10 backdrop-blur-xl rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 group transform hover:-translate-y-1 hover:shadow-lg">
                                <img src="{{ $admin->profile_photo_url }}" alt="{{ $admin->name }}" class="w-14 h-14 rounded-full ring-2 ring-white/50 group-hover:ring-white/80 transition-all">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-white text-xl drop-shadow-sm">{{ $admin->name }}</h3>
                                    <p class="text-white/70">{{ $admin->email }}</p>
                                </div>
                                <a href="{{ route('chat.show', $admin->id) }}" class="px-5 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300 backdrop-blur-sm border border-white/30 font-medium">
                                    Chatear
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="space-y-5">
                    <!-- Sección de empresas en la parte superior -->
                    <div class="flex justify-center items-center gap-8 mt-4 mb-12">
                        <div class="w-16 h-16 opacity-70 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            <img src="https://flowbite.com/docs/images/logo.svg" alt="Empresa">
                        </div>
                        <div class="w-16 h-16 opacity-70 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            <img src="https://flowbite.com/docs/images/logo.svg" alt="Empresa">
                        </div>
                        <div class="w-16 h-16 opacity-70 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            <img src="https://flowbite.com/docs/images/logo.svg" alt="Empresa">
                        </div>
                        <div class="w-16 h-16 opacity-70 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            <img src="https://flowbite.com/docs/images/logo.svg" alt="Empresa">
                        </div>
                        <div class="w-16 h-16 opacity-70 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            <img src="https://flowbite.com/docs/images/logo.svg" alt="Empresa">
                        </div>
                    </div>

                    <!-- Lista de conversaciones -->
                    @foreach($conversations as $userId => $conversation)
                        @php
                            $otherUser = $conversation['last_message']->sender_id === auth()->id()
                                ? $conversation['last_message']->receiver
                                : $conversation['last_message']->sender;
                        @endphp
                        <a href="{{ route('chat.show', $otherUser->id) }}" class="block transform transition-all duration-300 hover:-translate-y-1">
                            <div class="backdrop-blur-xl bg-white/10 rounded-2xl shadow-xl p-5 hover:bg-white/15 transition-all duration-300 border border-white/20">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}" class="w-14 h-14 rounded-full ring-2 ring-white/50">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h2 class="text-xl font-semibold text-white drop-shadow-sm truncate">
                                                {{ $otherUser->name }}
                                            </h2>
                                            <span class="text-sm text-white/70">
                                                {{ $conversation['last_message']->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-white/90 truncate">
                                            {{ $conversation['last_message']->message }}
                                        </p>
                                    </div>
                                    @if($conversation['unread_count'] > 0)
                                        <span class="inline-flex items-center justify-center px-3 py-1.5 min-w-[24px] h-6 text-xs font-bold leading-none text-white bg-gradient-to-r from-pink-500 to-violet-500 rounded-full shadow-lg">
                                            {{ $conversation['unread_count'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach

                    <!-- Sección de contactos disponibles -->
                    <div class="mt-8 backdrop-blur-xl bg-white/10 rounded-3xl shadow-2xl p-6 border border-white/20">
                        <h2 class="text-xl font-bold text-white mb-4">Contactos disponibles</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($admins as $admin)
                                <div class="flex items-center space-x-3 p-3 bg-white/10 backdrop-blur-xl rounded-xl hover:bg-white/15 transition-all duration-300 border border-white/20 group transform hover:-translate-y-1">
                                    <img src="{{ $admin->profile_photo_url }}" alt="{{ $admin->name }}" class="w-10 h-10 rounded-full ring-2 ring-white/30 group-hover:ring-white/60 transition-all">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-white text-md">{{ $admin->name }}</h3>
                                        <p class="text-white/70 text-xs">{{ $admin->email }}</p>
                                    </div>
                                    <a href="{{ route('chat.show', $admin->id) }}" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-sm rounded-lg shadow transform group-hover:scale-105 transition-all duration-300 backdrop-blur-sm border border-white/30">
                                        Chatear
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sección "Más rápido, más inteligente" -->
                    <div class="mt-16 px-8 py-10 backdrop-blur-xl bg-white/5 rounded-3xl border border-white/10 text-center">
                        <h2 class="text-2xl font-bold text-white mb-4">Más rápido. Más inteligente.</h2>
                        <p class="text-white/80 max-w-2xl mx-auto mb-6">
                            Este servicio de mensajería garantiza una comunicación instantánea y segura dentro del GAMT.
                            Disfruta de mensajes cifrados entre Adminsitradores y Usuarios.
                        </p>
                        <div class="flex justify-center">
                            <button class="px-6 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300 border border-white/30 font-medium">
                                ¡¡¡OK!!!
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado en chat/index');

    // Actualizar contador de mensajes no leídos cada 30 segundos
    setInterval(function() {
        fetch('{{ route("chat.unread") }}')
            .then(response => response.json())
            .then(data => {
                // Aquí puedes actualizar el contador en la UI si lo necesitas
            });
    }, 30000);
});
</script>
@endpush
