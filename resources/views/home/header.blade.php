<header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-40 transition-colors">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
                <span class="ml-2 font-bold text-red-600 dark:text-red-400">Sistema de Gestión Documental</span>
            </a>

            <!-- Navegación para escritorio -->
            <nav class="hidden md:flex items-center space-x-4">
                <a href="{{ route('home') }}" class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 rounded-md {{ request()->routeIs('home') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                    <i class="fas fa-home mr-1"></i>Inicio
                </a>
                <a href="{{ route('explore') }}" class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 rounded-md {{ request()->routeIs('explore') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                    <i class="fas fa-search mr-1"></i>Explorar
                </a>
                <a href="{{ route('books.history') }}" class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 rounded-md {{ request()->routeIs('books.history') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                    <i class="fas fa-history mr-1"></i>Mi Historial
                </a>

                <!-- Botón de Chat -->
                <a href="#"
                   @click.prevent="$dispatch('open-chat-modal')"
                   class="relative px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 rounded-md {{ request()->routeIs('chat.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                    <i class="fas fa-comments mr-1"></i>Chat
                    @if($unreadMessages > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                            {{ $unreadMessages }}
                        </span>
                    @endif
                </a>

                <!-- Perfil y Toggle de Tema -->
                <div class="flex items-center ml-4 space-x-2">
                    <!-- Toggle de tema -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-lg p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <!-- Menú de perfil -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full">
                            <span class="ml-1 text-sm font-medium hidden sm:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('perfil.show') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    Mi perfil
                                </div>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Botón menú móvil -->
            <button id="mobile-menu-button" class="md:hidden text-gray-700 dark:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Menú móvil -->
        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800 pb-4 transition-colors">
            <a href="{{ route('home') }}" class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 {{ request()->routeIs('home') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                <i class="fas fa-home mr-1"></i>Inicio
            </a>
            <a href="{{ route('explore') }}" class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 {{ request()->routeIs('explore') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                <i class="fas fa-search mr-1"></i>Explorar
            </a>
            <a href="{{ route('chat.index') }}" class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 {{ request()->routeIs('chat.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                <i class="fas fa-comments mr-1"></i>Chat
                @if($unreadMessages > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1">
                        {{ $unreadMessages }}
                    </span>
                @endif
            </a>
            <a href="{{ route('books.history') }}" class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 {{ request()->routeIs('books.history') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
                <i class="fas fa-history mr-1"></i>Mi Historial
            </a>
            <a href="{{ route('perfil.show') }}" class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user-circle"></i>
                    <span>Mi perfil</span>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="block py-2 px-4">
                @csrf
                <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400">
                    <i class="fas fa-sign-out-alt mr-1"></i>Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Modal para Iniciar Chat -->
<div x-data="{ open: false }"
     x-show="open"
     @keydown.escape.window="open = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"
     :class="{ 'hidden': !open }"
     x-transition>
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                 @click.away="open = false">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">
                        Iniciar conversación
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Seleccione un administrador para iniciar una conversación:
                    </p>

                    <div class="space-y-2 max-h-60 overflow-y-auto mb-4">
                        @foreach($admins as $admin)
                            <button @click="window.location.href='{{ route('chat.show', $admin->id) }}'"
                                    class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center space-x-3">
                                <img src="{{ $admin->profile_photo_url }}"
                                     alt="{{ $admin->name }}"
                                     class="w-10 h-10 rounded-full">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $admin->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Administrador
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                            @click="open = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modificar el botón de chat para usar Alpine.js -->
<a href="#"
   @click.prevent="$dispatch('open-chat-modal')"
   class="relative px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 rounded-md {{ request()->routeIs('chat.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : '' }}">
    <i class="fas fa-comments mr-1"></i>Chat
    @if($unreadMessages > 0)
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
            {{ $unreadMessages }}
        </span>
    @endif
</a>

<!-- Agregar el script de Alpine.js si no está incluido en el layout principal -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Escuchar el evento para abrir el modal -->
<script>
    document.addEventListener('alpine:init', () => {
        window.addEventListener('open-chat-modal', () => {
            const modalData = document.querySelector('[x-data]').__x.$data;
            modalData.open = true;
        });
    });
</script>

<script>
    // Toggle del menú móvil
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        menuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

        // Toggle del tema oscuro/claro
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        // Mostrar el icono correcto al cargar
        if (document.documentElement.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            darkIcon.classList.toggle('hidden');
            lightIcon.classList.toggle('hidden');

            // Guardar preferencia
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
        });
    });
</script>
