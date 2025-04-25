@extends('home.layouts.app')

@section('title', $data->title)

@section('body-class', 'leading-normal tracking-normal text-white m-6 bg-cover bg-fixed')

@section('styles')
<style>
    body {
        background-image: url("{{ asset('/images/header.png') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .detail-badge {
        @apply bg-white/10 backdrop-blur-sm text-white px-3 py-2 rounded-lg border border-white/30 flex items-center mb-3;
    }
    .detail-badge i {
        @apply mr-3 text-xl text-white/80;
    }
    .detail-badge span {
        @apply font-medium;
    }
    .detail-section {
        @apply mb-6;
    }
    .detail-section h2 {
        @apply text-xl font-semibold text-white mb-3 flex items-center;
    }
    .detail-section h2 i {
        @apply mr-2 text-white/80;
    }
    .book-stat {
        @apply bg-white/10 backdrop-blur-sm text-white text-sm px-3 py-2 rounded-full border border-white/30 flex items-center;
    }
    .book-stat i {
        @apply mr-2;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Navegación rápida -->
    <div class="mb-6">
        <a href="{{ route('explore') }}"
            class="inline-flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white
            px-4 py-2 rounded-lg transform transition hover:scale-105 duration-300 ease-in-out
            border border-white/30">
            <i class="fas fa-arrow-left mr-2"></i> Volver a explorar
        </a>
    </div>

    <div class="bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden shadow-xl border border-white/20">
        <!-- Encabezado con título y etiquetas -->
        <div class="bg-gradient-to-r from-purple-900/60 to-blue-900/60 p-6 border-b border-white/10">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 flex items-center">
                {{ $data->title }}
                <span class="ml-3 bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-pink-500 to-purple-500">
                    #{{ $data->N_codigo ?: $data->id }}
                </span>
            </h1>

            <p class="text-white/70 text-sm mb-3">
                <i class="fas fa-user-shield mr-1"></i> Responsable:
                <span class="font-medium">{{ $data->admin_name ?? (session('admin_name') ?? 'Administrador del Sistema') }}</span>
                <span class="mx-2">•</span>
                <i class="fas fa-qrcode mr-1"></i> Código:
                <span class="font-medium">{{ $data->N_codigo ?: ('10' . str_pad($data->id, 3, '0', STR_PAD_LEFT) . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2)) }}</span>
            </p>

            <div class="flex flex-wrap gap-2 mt-4">
                <span class="book-stat">
                    <i class="fas fa-tag"></i> {{ $data->category->cat_title ?? 'Sin categoría' }}
                </span>
                <span class="book-stat">
                    <i class="fas fa-calendar"></i> {{ $data->year }}
                </span>
                <span class="book-stat">
                    <i class="fas fa-book"></i> {{ $data->N_hojas }} páginas
                </span>
                <!-- Autor como etiqueta -->
                <span class="book-stat">
                    <i class="fas fa-user"></i> {{ $data->auther_name }}
                </span>
            </div>
        </div>

        <div class="md:flex">
            <!-- Imagen y acciones -->
            <div class="md:w-1/3 p-6 border-r border-white/10">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg overflow-hidden shadow-lg border border-white/20 mb-6">
                    @if($data->book_img)
                        <div class="relative group">
                            <img src="{{ asset('book/'.$data->book_img) }}"
                                alt="{{ $data->title }}"
                                class="w-full h-auto object-cover transform transition group-hover:scale-105 duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-4">
                                <span class="text-white text-sm font-medium">{{ $data->title }}</span>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-64 bg-gradient-to-r from-purple-900/40 to-blue-900/40 flex items-center justify-center">
                            <i class="fas fa-folder-open text-6xl text-white/50"></i>
                        </div>
                    @endif
                </div>

                <!-- Información clave en tarjetas -->
                <div class="space-y-4 mb-6">
                    <div class="detail-badge">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <div class="text-xs text-white/60">Ubicación</div>
                            <span>{{ $data->ubicacion }}</span>
                        </div>
                    </div>

                    <div class="detail-badge">
                        <i class="fas fa-barcode"></i>
                        <div>
                            <div class="text-xs text-white/60">Código Físico</div>
                            <span>{{ $data->tomo }}</span>
                        </div>
                    </div>

                    <div class="detail-badge">
                        <i class="fas fa-qrcode"></i>
                        <div>
                            <div class="text-xs text-white/60">Código Digital</div>
                            <span>{{ $data->N_codigo ?: ('10' . str_pad($data->id, 3, '0', STR_PAD_LEFT) . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2)) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botón de solicitud -->
                <div>
                    <a href="{{ route('books.borrow', $data->id) }}"
                        class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center
                        py-3 px-4 rounded-lg transform transition hover:scale-105 duration-300 ease-in-out
                        border border-white/30 shadow-lg">
                        <i class="fas fa-book-reader mr-2"></i> Solicitar Préstamo
                    </a>
                </div>
            </div>

            <!-- Detalles del libro -->
            <div class="md:w-2/3 p-6">
                <!-- Descripción -->
                <div class="detail-section">
                    <h2><i class="fas fa-info-circle"></i> Descripción</h2>
                    <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10">
                        <p class="text-white/80 leading-relaxed">{{ $data->description }}</p>
                    </div>
                </div>

                <!-- Detalles adicionales que podrían añadirse -->
                <div class="detail-section">
                    <h2><i class="fas fa-star"></i> Características</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10 flex items-center">
                            <i class="fas fa-language text-2xl text-white/60 mr-3"></i>
                            <div>
                                <div class="text-xs text-white/60">Idioma</div>
                                <div class="text-white font-medium">Español</div>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10 flex items-center">
                            <i class="fas fa-clock text-2xl text-white/60 mr-3"></i>
                            <div>
                                <div class="text-xs text-white/60">Disponibilidad</div>
                                <div class="text-white font-medium">{{ $data->estado ?? 'Disponible' }}</div>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10 flex items-center">
                            <i class="fas fa-file-pdf text-2xl text-white/60 mr-3"></i>
                            <div>
                                <div class="text-xs text-white/60">Formato</div>
                                <div class="text-white font-medium">Físico{{ isset($data->pdf_file) ? ' y Digital' : '' }}</div>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10 flex items-center">
                            <i class="fas fa-eye text-2xl text-white/60 mr-3"></i>
                            <div>
                                <div class="text-xs text-white/60">Vistas</div>
                                <div class="text-white font-medium">{{ $data->views ?? rand(5, 20) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del administrador responsable -->
                <div class="detail-section">
                    <h2><i class="fas fa-user-shield"></i> Administrador Responsable</h2>
                    <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-900/40 to-purple-900/40 flex items-center justify-center mr-3 border border-white/30">
                                <i class="fas fa-user-tie text-xl text-white/70"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-white">Administrador del Sistema</h3>
                                <p class="text-white/60 text-sm">Responsable de aprobar solicitudes</p>
                            </div>
                        </div>
                        <div class="mt-3 text-white/70 text-sm">
                            <p>Para consultas sobre este material, puede contactar con el administrador a través del chat después de realizar la solicitud.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de alerta -->
    @if(session('message') || session('error') || session('info'))
        <div class="mt-6">
            @if(session('message'))
                <div class="bg-white/10 backdrop-blur-sm border border-green-400 text-white px-4 py-4 rounded-lg mb-4">
                    <h3 class="font-bold text-lg mb-2">{{ session('message') }}</h3>
                    @if(session('details'))
                        <p class="mb-4">{{ session('details') }}</p>
                    @endif

                    @if(session('show_chat') && session('admin_id') && session('borrow_id'))
                        <a href="{{ route('chat.show', ['user' => session('admin_id'), 'borrow_id' => session('borrow_id')]) }}"
                           class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-comments mr-2"></i> Consultar sobre esta solicitud
                        </a>
                    @endif
                </div>
            @endif

            @if(session('info'))
                <div class="bg-white/10 backdrop-blur-sm border border-blue-400 text-white px-4 py-4 rounded-lg mb-4">
                    <p class="font-medium">{{ session('info') }}</p>

                    @if(session('show_chat') && session('borrow_id'))
                        <div class="mt-3">
                            <a href="{{ route('chat.show', ['user' => 1, 'borrow_id' => session('borrow_id')]) }}"
                               class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-comments mr-2"></i> Consultar sobre esta solicitud
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="bg-white/10 backdrop-blur-sm border border-red-400 text-white px-4 py-3 rounded-lg">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Modal de confirmación de solicitud -->
    <div id="request-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-gray-800 text-white rounded-xl shadow-xl w-11/12 max-w-lg p-6 mx-4">
            <div class="text-center">
                <div class="mb-4 mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-green-500/20 border border-green-500/40">
                    <i class="fas fa-check-circle text-3xl text-green-500"></i>
                </div>
                <h2 class="text-xl font-semibold mb-2">¡Solicitud Enviada!</h2>
                <p class="mb-6 text-gray-300 text-sm">
                    Tu solicitud para la carpeta "<span id="modal-book-title" class="font-medium"></span>" ha sido enviada.
                    Un administrador revisará tu solicitud pronto.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a id="chat-admin-link" href="#" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-comments mr-2"></i> Chatear con administrador
                    </a>
                    <button id="close-modal" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para manejo de solicitudes -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Si hay un mensaje de sesión, mostrar el modal
        @if(session('message') && session('borrow_id') && session('admin_id'))
            document.getElementById('modal-book-title').textContent = '{{ $data->title }}';
            document.getElementById('chat-admin-link').href = '{{ route('chat.show', '') }}/' + '{{ session('admin_id') }}' + '?borrow_id=' + '{{ session('borrow_id') }}';
            document.getElementById('request-modal').classList.remove('hidden');
        @endif

        // Configurar el botón de solicitud
        const requestButton = document.querySelector('a[href="{{ route('books.borrow', $data->id) }}"]');
        if (requestButton) {
            requestButton.addEventListener('click', function(e) {
                e.preventDefault();
                solicitarCarpeta({{ $data->id }}, '{{ $data->title }}');
            });
        }

        // Función para solicitar carpeta
        function solicitarCarpeta(id, title) {
            // Realizar la solicitud AJAX al servidor
            fetch(`/books/borrow/${id}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar modal de éxito
                    document.getElementById('modal-book-title').textContent = title;
                    document.getElementById('chat-admin-link').href = '{{ route('chat.show', '') }}/' + data.admin_id + '?borrow_id=' + data.borrow_id;
                    document.getElementById('request-modal').classList.remove('hidden');
                } else {
                    // Mostrar error
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un problema al enviar la solicitud. Por favor, inténtalo de nuevo.');
            });
        }

        // Cerrar modal con botón
        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('request-modal').classList.add('hidden');
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('request-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
    </script>
</div>
@endsection
