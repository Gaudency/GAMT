<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TOMAVETAIL</title>
    @include('admin.css')
    <style>
        @keyframes gradienteAnimado {
            0% {
                background-position: 0% 50%;
                background-image: linear-gradient(to right, #ff0080, #ff8c00, #40e0d0, #7000ff);
            }
            50% {
                background-position: 100% 50%;
                background-image: linear-gradient(to right, #7000ff, #40e0d0, #ff8c00, #ff0080);
            }
            100% {
                background-position: 0% 50%;
                background-image: linear-gradient(to right, #ff0080, #ff8c00, #40e0d0, #7000ff);
            }
        }

        .titulo-animado {
            background-size: 300% 300%;
            animation: gradienteAnimado 8s ease infinite;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        // Asegurarnos que el modo oscuro se aplica correctamente
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Definimos la función toggleDarkMode en el ámbito global (window)
        window.toggleDarkMode = function() {
            console.log('toggleDarkMode called');
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
                console.log('Dark mode disabled');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
                console.log('Dark mode enabled');
            }
        };
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    @include('admin.header')

    <div class="flex flex-col min-h-screen">
        <main class="flex-grow w-full p-6 mt-20 transition-all duration-300">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold mb-8 text-center text-transparent bg-clip-text titulo-animado">Panel General de Administración</h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Tarjeta 1: Usuarios -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ route('users.index') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Usuarios</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Gestión de usuarios</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 2: Categorías -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ url('category_page') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Categorías</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Gestión de categorías</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 3: Buscar -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ url('advanced') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Buscar</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Búsqueda avanzada</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 4: Ver Carpetas -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ url('show_book') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Ver Carpetas</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Explorar carpetas</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 5: Solicitudes en Línea -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ url('borrow_request') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Solicitudes Online</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Gestionar solicitudes</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 6: Préstamos de Carpetas -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ route('document.loans.index') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Solicitudes Offline</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Control de préstamos</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 7: Reportes -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ route('reporte.index') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Reportes</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Informes y estadísticas</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 8: Préstamos -->
                    <div class="transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ route('loose-loans.index') }}" class="block h-full">
                            <div class="flex flex-col h-full rounded-lg overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 shadow-lg hover:shadow-xl">
                                <div class="flex-grow p-6 flex flex-col items-center justify-center space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Préstamos</h2>
                                </div>
                                <div class="bg-white/10 p-4">
                                    <p class="text-sm text-white text-center">Hojas sueltas</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>


