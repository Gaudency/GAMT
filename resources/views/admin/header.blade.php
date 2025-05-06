<!-- Botón para ocultar/mostrar la barra de navegación
<button id="sidebarToggle" class="fixed right-3 top-3 p-2.5 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 z-[1002]">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 toggle-icon transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
</button>
  -->
<!-- Definir la función toggleDarkMode directamente aquí para asegurar que existe -->
<script>
    function toggleDarkMode() {
        console.log('toggleDarkMode llamada directamente');
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

    // Para manejar el menú de perfil con JavaScript puro
    document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileMenu');

        if (profileButton && profileMenu) {
            // Mostrar/ocultar el menú al hacer clic
            profileButton.addEventListener('click', function(e) {
                e.preventDefault();
                profileMenu.classList.toggle('hidden');
            });

            // Cerrar el menú al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });
        }
    });
</script>

<header id="mainHeader" class="fixed w-full top-0 left-0 bg-white/95 dark:bg-gray-900/95 shadow-md transition-all duration-300 z-[1000] backdrop-blur-sm lg:pl-64">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div class="py-4 flex items-center justify-between">
                     <!-- Logo modo claro/oscuro -->
                     <div class="shrink-0 flex items-center">
                        <a href="{{ route('admin.dashboard') }}">
                            <!-- Logo para modo claro -->
                            <img src="{{ asset('images/claro.svg') }}" alt="Logo Claro" class="h-auto w-auto block dark:hidden">

                            <!-- Logo para modo oscuro -->
                            <img src="{{ asset('images/oscuro.svg') }}" alt="Logo Oscuro" class="h-auto w-auto hidden dark:block">
                        </a>
                    </div>

                    <div class="flex items-center">
                        <style>
                            .texto-animado {
                                display: inline-block;
                                text-shadow: 0 1px 2px rgba(0,0,0,0.1);
                            }

                            .texto-animado span {
                                display: inline-block;
                                animation: fluctuar 10s infinite ease-in-out;
                                transform-origin: bottom center;
                            }

                            @keyframes fluctuar {
                                0%, 100% {
                                    transform: translateY(0) scale(1);
                                    color: inherit;
                                }
                                1.5% {
                                    transform: translateY(-6px) scale(1.2);
                                    color: #ff0080;
                                }
                                3% {
                                    transform: translateY(0) scale(1);
                                    color: inherit;
                                }
                            }

                            /* Retraso para cada letra */
                            .texto-animado span:nth-child(1) { animation-delay: 0s; }
                            .texto-animado span:nth-child(2) { animation-delay: 0.2s; }
                            .texto-animado span:nth-child(3) { animation-delay: 0.4s; }
                            .texto-animado span:nth-child(4) { animation-delay: 0.6s; }
                            .texto-animado span:nth-child(5) { animation-delay: 0.8s; }
                            .texto-animado span:nth-child(6) { animation-delay: 1s; }
                            .texto-animado span:nth-child(7) { animation-delay: 1.2s; }
                            .texto-animado span:nth-child(8) { animation-delay: 1.4s; }
                            .texto-animado span:nth-child(9) { animation-delay: 1.6s; }
                            .texto-animado span:nth-child(10) { animation-delay: 1.8s; }
                            .texto-animado span:nth-child(11) { animation-delay: 2s; }
                            .texto-animado span:nth-child(12) { animation-delay: 2.2s; }
                            .texto-animado span:nth-child(13) { animation-delay: 2.4s; }
                            .texto-animado span:nth-child(14) { animation-delay: 2.6s; }
                            .texto-animado span:nth-child(15) { animation-delay: 2.8s; }
                            .texto-animado span:nth-child(16) { animation-delay: 3s; }
                            .texto-animado span:nth-child(17) { animation-delay: 3.2s; }
                            .texto-animado span:nth-child(18) { animation-delay: 3.4s; }
                            .texto-animado span:nth-child(19) { animation-delay: 3.6s; }
                            .texto-animado span:nth-child(20) { animation-delay: 3.8s; }
                            .texto-animado span:nth-child(21) { animation-delay: 4s; }
                            .texto-animado span:nth-child(22) { animation-delay: 4.2s; }
                            .texto-animado span:nth-child(23) { animation-delay: 4.4s; }
                            .texto-animado span:nth-child(24) { animation-delay: 4.6s; }
                            .texto-animado span:nth-child(25) { animation-delay: 4.8s; }
                            .texto-animado span:nth-child(26) { animation-delay: 5s; }
                            .texto-animado span:nth-child(27) { animation-delay: 5.2s; }
                            .texto-animado span:nth-child(28) { animation-delay: 5.4s; }
                            .texto-animado span:nth-child(29) { animation-delay: 5.6s; }
                            .texto-animado span:nth-child(30) { animation-delay: 5.8s; }
                            .texto-animado span:nth-child(31) { animation-delay: 6s; }
                            .texto-animado span:nth-child(32) { animation-delay: 6.2s; }
                            .texto-animado span:nth-child(33) { animation-delay: 6.4s; }
                            .texto-animado span:nth-child(34) { animation-delay: 6.6s; }
                            .texto-animado span:nth-child(35) { animation-delay: 6.8s; }
                            .texto-animado span:nth-child(36) { animation-delay: 7s; }
                            .texto-animado span:nth-child(37) { animation-delay: 7.2s; }
                            .texto-animado span:nth-child(38) { animation-delay: 7.4s; }
                            .texto-animado span:nth-child(39) { animation-delay: 7.6s; }
                            .texto-animado span:nth-child(40) { animation-delay: 7.8s; }
                        </style>
                        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100 texto-animado">
                            <span>S</span><span>i</span><span>s</span><span>t</span><span>e</span><span>m</span><span>a</span>
                            <span> </span>
                            <span>d</span><span>e</span>
                            <span> </span>
                            <span>G</span><span>e</span><span>s</span><span>t</span><span>i</span><span>o</span><span>n</span>
                            <span> </span>
                            <span>d</span><span>o</span><span>c</span><span>u</span><span>m</span><span>e</span><span>n</span><span>t</span><span>a</span><span>l</span>
                            <span> </span>
                            <span>d</span><span>e</span>
                            <span> </span>
                            <span>T</span><span>o</span><span>m</span><span>a</span><span>v</span><span>e</span><span>😀</span>
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                            <!-- Botón para abrir el chat de IA
                        <button
                            onclick="console.log('Botón header clickeado'); window.dispatchEvent(new CustomEvent('toggle-ai-chat')); return false;"
                            class="ml-3 w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 flex items-center justify-center text-white transition-all duration-300 shadow-md hover:shadow-lg hover:scale-110 focus:outline-none"
                            title="Asistente IA">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 001.5 2.25M9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                            </svg>
                        </button> -->
                        <!-- Notificaciones de Chat -->
                        <div class="relative">
                            <a href="{{ route('chat.index') }}" class="relative w-10 h-10 rounded-full bg-gradient-to-r from-blue-500/80 to-purple-600/80 hover:from-blue-600 hover:to-purple-700 flex items-center justify-center text-white transition-all duration-300 shadow-md hover:shadow-lg hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                @if(isset($unreadMessages) && $unreadMessages > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                        {{ $unreadMessages }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Botón para cambiar entre modo claro/oscuro -->
                        <button id="themeToggle" onclick="toggleDarkMode()" class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 flex items-center justify-center text-white transition-all duration-300 shadow-md hover:shadow-lg hover:scale-110 focus:outline-none" title="Cambiar tema">
                            <!-- Icono sol (modo oscuro) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Icono luna (modo claro) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>



                        <!-- Perfil de usuario -->
                        <div class="relative">
                            <button id="profileButton" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 focus:outline-none">
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ asset(auth()->user()->profile_photo_path) }}" alt="Perfil" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <img src="{{ asset('admin/img/user.jpg') }}" alt="Perfil" class="h-10 w-10 rounded-full object-cover">
                                @endif
                                <span class="hidden sm:block">Admin</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md py-2 z-50">
                                <a href="{{ route('perfil.show') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Perfil
                                    </div>
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Menu Principal
                                    </div>
                                </a>
                                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 transition-colors duration-200">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Cerrar Sesión
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


