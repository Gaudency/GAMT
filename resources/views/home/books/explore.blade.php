@extends('home.layouts.app')

@section('title', 'Explorar Carpetas')

@section('body-class', 'leading-normal tracking-normal text-white m-6 bg-cover bg-fixed')

@section('styles')
<style>
    body {
        background-image: url("{{ asset('/images/header.png') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .modal {
        transition: opacity 0.25s ease;
    }

    /* Estilos para el select y opciones */
    select {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        -webkit-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg fill='white' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    /* Estilo para las opciones del select */
    select option {
        background-color: rgba(40, 40, 42, 0.95);
        color: white;
        backdrop-filter: blur(10px);
    }
</style>
@endsection

@section('content')
    @include('home.components.alert')

    <!-- Barra de navegación -->
    @include('home.components.navbar')

    <div class="min-h-screen py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-white mb-8 text-center">
                Explorar
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-pink-500 to-purple-500">
                    Carpetas
                </span>
            </h1>

            <!-- Filtros de búsqueda -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 mb-8">
                <form action="{{ route('search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label for="search" class="block text-white text-sm font-medium mb-2">Buscar por título</label>
                        <input type="text" name="search" id="search"
                            class="w-full bg-white/10 text-white border border-white/30 rounded-lg px-4 py-2
                            focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white/30
                            transition duration-300"
                            placeholder="Título de carpeta..."
                            value="{{ request('search') }}">
                    </div>

                    <div>
                        <label for="codigo" class="block text-white text-sm font-medium mb-2">Código Digital</label>
                        <input type="text" name="codigo" id="codigo"
                            class="w-full bg-white/10 text-white border border-white/30 rounded-lg px-4 py-2
                            focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white/30
                            transition duration-300"
                            placeholder="Ej: 1001-SD, 1050-AH..."
                            value="{{ request('codigo') }}">
                    </div>

                    <div>
                        <label for="category" class="block text-white text-sm font-medium mb-2">Categoría</label>
                        <select name="category" id="category"
                            class="w-full bg-white/10 text-white border border-white/30 rounded-lg px-4 py-2
                            focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white/30
                            transition duration-300">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->cat_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-white/20 backdrop-blur-sm hover:bg-white/30
                            text-white font-medium py-2 px-4 rounded-lg
                            transform transition hover:scale-105 duration-300 ease-in-out
                            border border-white/30">
                            <i class="fas fa-search mr-2"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg overflow-hidden shadow-lg
                        transform transition hover:scale-105 hover:bg-white/15 duration-300 ease-in-out
                        border border-white/20">
                        <div class="relative h-48 overflow-hidden">
                            @if($book->book_img)
                                <img src="{{ asset('book/'.$book->book_img) }}"
                                    alt="{{ $book->title }}"
                                    class="w-full h-full object-cover transform transition hover:scale-110 duration-500">
                            @else
                                <div class="w-full h-full bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                    <i class="fas fa-folder-open text-4xl text-white/50"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-white mb-1 truncate">{{ $book->title }}</h3>
                            <p class="text-white/70 text-sm mb-2">{{ $book->ubicacion }}</p>
                            <p class="text-white/60 text-xs mb-4">
                                <i class="fas fa-tag mr-1"></i> {{ $book->category->cat_title ?? 'Sin categoría' }}
                            </p>
                            <div class="flex space-x-2">
                                <a href="{{ route('books.details', $book->id) }}"
                                    class="flex-1 bg-white/20 backdrop-blur-sm hover:bg-white/30
                                    text-white text-center py-2 px-3 rounded-lg text-sm
                                    transform transition hover:scale-105 duration-300 ease-in-out
                                    border border-white/30">
                                    <i class="fas fa-info-circle mr-1"></i> Detalles
                                </a>
                                <button onclick="solicitarCarpeta({{ $book->id }}, '{{ $book->title }}')"
                                    class="flex-1 bg-white/20 backdrop-blur-sm hover:bg-white/30
                                    text-white text-center py-2 px-3 rounded-lg text-sm
                                    transform transition hover:scale-105 duration-300 ease-in-out
                                    border border-white/30">
                                    <i class="fas fa-book-reader mr-1"></i> +Info
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white/10 backdrop-blur-sm rounded-lg p-8 text-center
                        border border-white/20">
                        <i class="fas fa-folder-open text-5xl text-white/50 mb-4"></i>
                        <h3 class="text-xl text-white mb-2">No se encontraron carpetas</h3>
                        <p class="text-white/70">Intenta con otros criterios de búsqueda o explora las categorías destacadas.</p>
                    </div>
                @endforelse
            </div>

            <!-- Modal de chat -->
            <div id="chatModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Iniciar Chat con Administrador
                            </h3>
                            <div class="space-y-4">
                                @foreach($admins as $admin)
                                    <a href="{{ route('chat.show', $admin->id) }}"
                                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <img src="{{ $admin->profile_photo_url }}"
                                             alt="{{ $admin->name }}"
                                             class="w-10 h-10 rounded-full">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $admin->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $admin->email }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button onclick="cerrarModal()"
                                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

            <!-- Paginación -->
            <div class="mt-8">
                {{ $books->links() }}
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('home.components.footer')
@endsection

@push('scripts')
<script>
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

document.getElementById('close-modal').addEventListener('click', function() {
    document.getElementById('request-modal').classList.add('hidden');
});

// Cerrar modal al hacer clic fuera
document.getElementById('request-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

function cerrarModal() {
    document.getElementById('chatModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('chatModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>
@endpush
