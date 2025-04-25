<nav class="bg-gradient-to-r from-red-600/30 to-white/30 dark:from-violet-900/30 dark:to-white/20 backdrop-blur-sm shadow-lg py-4 mb-8 border-b border-white/10">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <!-- Logo y Título -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <img src="{{ asset('images/logo.png') }}" alt="GAMT Logo" class="h-16 w-auto block dark:hidden transform transition-transform group-hover:scale-110 duration-300">
                <img src="{{ asset('images/bandera.png') }}" alt="GAMT Logo" class="h-16 w-auto hidden dark:block transform transition-transform group-hover:scale-110 duration-300">
                <span class="text-white font-bold text-xl md:text-2xl bg-clip-text text-transparent bg-gradient-to-r from-red-400 to-red-600 dark:from-purple-400 dark:to-purple-600">
                    GAMT<span class="text-white">Docs</span>
                </span>
            </a>

            <!-- Navegación desktop -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('home') }}"
                    class="text-white hover:bg-white/10 transition-all duration-300 px-3 py-2 rounded-lg
                    {{ request()->routeIs('home') ? 'bg-white/20 shadow-lg' : '' }}">
                    <i class="fas fa-home mr-1"></i> Inicio
                </a>
                <a href="{{ route('explore') }}"
                    class="text-white hover:bg-white/10 transition-all duration-300 px-3 py-2 rounded-lg
                    {{ request()->routeIs('explore') ? 'bg-white/20 shadow-lg' : '' }}">
                    <i class="fas fa-search mr-1"></i> Explorar
                </a>
                <a href="{{ route('books.history') }}"
                    class="text-white hover:bg-white/10 transition-all duration-300 px-3 py-2 rounded-lg
                    {{ request()->routeIs('books.history') ? 'bg-white/20 shadow-lg' : '' }}">
                    <i class="fas fa-history mr-1"></i> Mi Historial
                </a>
                <a href="{{ route('chat.index') }}"
                    class="text-white hover:bg-white/10 transition-all duration-300 px-3 py-2 rounded-lg
                    {{ request()->routeIs('chat.*') ? 'bg-white/20 shadow-lg' : '' }}">
                    <i class="fas fa-comments mr-1"></i> Chat
                    @if($unreadMessages > 0)
                        <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $unreadMessages }}
                        </span>
                    @endif
                </a>

                <!-- Toggle de tema y dropdown de perfil -->
                <div class="flex items-center ml-4 space-x-2">
                    <!-- Toggle de tema
                    <button id="theme-toggle" type="button"
                        class="bg-white/10 backdrop-blur-sm p-2 rounded-lg hover:bg-white/20
                        transform transition-all hover:scale-105 duration-300 border border-white/20">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                        -->
                    @auth
                    <!-- Dropdown de perfil -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center bg-white/10 backdrop-blur-sm px-3 py-2 rounded-lg hover:bg-white/20
                            transform transition-all hover:scale-105 duration-300 border border-white/20">
                            <img src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}"
                                class="h-8 w-8 rounded-full object-cover border-2 border-white/50">
                            <span class="ml-2 text-white hidden lg:block">{{ Auth::user()->name }}</span>
                            <svg class="ml-2 w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white/10 backdrop-blur-sm border border-white/20
                            rounded-lg shadow-lg py-2 z-10">
                            <a href="{{ route('perfil.show') }}"
                                class="block px-4 py-2 text-white hover:bg-white/20 transition-all duration-300">
                                <i class="fas fa-user-circle mr-2"></i> Mi Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-white hover:bg-white/20 transition-all duration-300">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}"
                        class="bg-white/10 backdrop-blur-sm px-3 py-2 rounded-lg hover:bg-white/20
                        transform transition-all hover:scale-105 duration-300 border border-white/20 text-white">
                        <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesión
                    </a>
                    @endauth
                </div>
            </div>

            <!-- Menú móvil -->
            <div class="md:hidden flex items-center space-x-2">
                <!-- Toggle de tema -->
                <button id="theme-toggle-mobile" type="button"
                    class="bg-white/10 backdrop-blur-sm p-2 rounded-lg hover:bg-white/20
                    transform transition-all hover:scale-105 duration-300 border border-white/20">
                    <svg id="theme-toggle-dark-icon-mobile" class="hidden w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon-mobile" class="hidden w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Botón de menú -->
                <button id="mobile-menu-button"
                    class="bg-white/10 backdrop-blur-sm p-2 rounded-lg hover:bg-white/20
                    transform transition-all hover:scale-105 duration-300 border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menú móvil desplegable -->
        <div id="mobile-menu" class="md:hidden hidden mt-4 pt-4 border-t border-white/20">
            <div class="space-y-2">
                <a href="{{ route('home') }}"
                    class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300
                    {{ request()->routeIs('home') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>
                <a href="{{ route('explore') }}"
                    class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300
                    {{ request()->routeIs('explore') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-search mr-2"></i> Explorar
                </a>
                <a href="{{ route('books.history') }}"
                    class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300
                    {{ request()->routeIs('books.history') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-history mr-2"></i> Mi Historial
                </a>
                <a href="{{ route('chat.index') }}"
                    class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300
                    {{ request()->routeIs('chat.*') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-comments mr-2"></i> Chat
                    @if($unreadMessages > 0)
                        <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $unreadMessages }}
                        </span>
                    @endif
                </a>
                @auth
                <a href="{{ route('perfil.show') }}"
                    class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300">
                    <i class="fas fa-user-circle mr-2"></i> Mi Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}"
                    class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                </a>
                <a href="{{ route('register') }}"
                    class="block py-2 px-3 text-white hover:bg-white/10 rounded-lg transition-all duration-300">
                    <i class="fas fa-user-plus mr-2"></i> Registrarse
                </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    // Toggle del menú móvil
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Función para actualizar los iconos del tema
        function updateThemeIcons(isDark) {
            const darkIcons = [
                document.getElementById('theme-toggle-dark-icon'),
                document.getElementById('theme-toggle-dark-icon-mobile')
            ];
            const lightIcons = [
                document.getElementById('theme-toggle-light-icon'),
                document.getElementById('theme-toggle-light-icon-mobile')
            ];

            darkIcons.forEach(icon => {
                if (icon) icon.classList.toggle('hidden', isDark);
            });
            lightIcons.forEach(icon => {
                if (icon) icon.classList.toggle('hidden', !isDark);
            });
        }

        // Inicializar el estado del tema
        updateThemeIcons(document.documentElement.classList.contains('dark'));

        // Event listeners para los botones de tema
        ['theme-toggle', 'theme-toggle-mobile'].forEach(id => {
            const button = document.getElementById(id);
            if (button) {
                button.addEventListener('click', function() {
                    document.documentElement.classList.toggle('dark');
                    const isDark = document.documentElement.classList.contains('dark');
                    updateThemeIcons(isDark);
                    localStorage.theme = isDark ? 'dark' : 'light';
                });
            }
        });
    });
</script>
