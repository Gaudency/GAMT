@php
use Illuminate\Support\Facades\Auth;
@endphp

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-[70px] right-[-320px] w-[300px] h-[calc(100vh-90px)] bg-white dark:bg-gray-800 rounded-l-xl shadow-lg z-50 overflow-y-auto transition-all duration-300 ease-out border-l border-gray-200 dark:border-gray-700 backdrop-blur-md transform">
    <div class="p-6">
        <div class="flex flex-col justify-center items-center space-y-3 mb-8">
            @if(Auth::check() && Auth::user()->profile_photo_path)
                <img src="{{ asset(Auth::user()->profile_photo_path) }}"
                     alt="{{ Auth::user()->name }}"
                     class="h-16 w-16 rounded-full object-cover border-2 border-red-500 shadow-md">
            @else
                <img src="{{ asset('admin/img/user.jpg') }}"
                     alt="Usuario"
                     class="h-16 w-16 rounded-full object-cover border-2 border-red-500 shadow-md">
            @endif
            <span class="text-lg font-bold text-gray-900 dark:text-white">
                {{ Auth::check() ? Auth::user()->name : 'Usuario' }}
            </span>
        </div>

        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Menu
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Usuarios
                    </a>
                </li>
                <li>
                    <a href="{{ url('category_page') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Categorías
                    </a>
                </li>
                <li>
                    <a href="{{ url('advanced') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Búsqueda
                    </a>
                </li>
                <li>
                    <a href="{{ url('show_book') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </svg>
                        Ver Carpetas
                    </a>
                </li>
                <li>
                    <a href="{{ url('borrow_request') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Solicitudes Online
                    </a>
                </li>
                <li>
                    <a href="{{ route('document.loans.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Solicitudes Offline
                    </a>
                </li>
                <li>
                    <a href="{{ route('reporte.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Reportes
                    </a>
                </li>
                <li>
                    <a href="{{ route('loose-loans.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0016 19v-5" />
                        </svg>
                        Préstamos Sueltos
                    </a>
                </li>
                <li>
                    <a href="{{ route('backups.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 rounded transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0016 19v-5" />
                        </svg>
                        Backups
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Overlay para cuando el sidebar está abierto -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-0 z-40 hidden transition-all duration-300"></div>

<!-- Botón para abrir/cerrar el sidebar -->
<button id="sidebarToggleBtn" class="fixed top-20 right-5 w-12 h-12 rounded-full bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 flex items-center justify-center shadow-md hover:scale-110 transition-all duration-300 z-50">
    <i class="fas fa-bars text-lg toggle-icon"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const icon = toggleBtn.querySelector('.toggle-icon');
    const overlay = document.getElementById('sidebarOverlay');

    // Recuperar estado del sidebar
    const isOpen = localStorage.getItem('sidebarOpen') === 'true';

    // Función para aplicar el estado del sidebar
    function applySidebarState(open) {
        if (open) {
            sidebar.classList.add('right-0');
            sidebar.classList.remove('right-[-320px]');
            overlay.classList.remove('hidden', 'bg-opacity-0');
            overlay.classList.add('bg-opacity-40');
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            sidebar.classList.remove('right-0');
            sidebar.classList.add('right-[-320px]');
            overlay.classList.add('hidden', 'bg-opacity-0');
            overlay.classList.remove('bg-opacity-40');
            icon.classList.add('fa-bars');
            icon.classList.remove('fa-times');
        }
    }

    // Aplicar estado inicial
    applySidebarState(isOpen);

    // Función para alternar el sidebar
    function toggleSidebar() {
        const isCurrentlyOpen = sidebar.classList.contains('right-0');
        applySidebarState(!isCurrentlyOpen);
        localStorage.setItem('sidebarOpen', !isCurrentlyOpen);
    }

    // Evento para el botón de toggle
    toggleBtn.addEventListener('click', toggleSidebar);

    // Cerrar el sidebar al hacer clic en el overlay
    overlay.addEventListener('click', function() {
        applySidebarState(false);
        localStorage.setItem('sidebarOpen', 'false');
    });

    // Cerrar el sidebar al hacer clic en un enlace (en dispositivos móviles)
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) { // Solo en dispositivos móviles
                applySidebarState(false);
                localStorage.setItem('sidebarOpen', 'false');
            }
        });
    });

    // Cerrar el sidebar al presionar Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('right-0')) {
            applySidebarState(false);
            localStorage.setItem('sidebarOpen', 'false');
        }
    });

    // Asegurarse de que el modo oscuro/claro se aplique correctamente
    function applyTheme() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isDarkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    // Aplicar tema inicial
    applyTheme();

    // Escuchar cambios en el tema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyTheme);

    // Escuchar eventos de cambio de tema
    window.addEventListener('storage', function(e) {
        if (e.key === 'darkMode') {
            applyTheme();
        }
    });
});
</script>

<!-- FontAwesome para íconos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
