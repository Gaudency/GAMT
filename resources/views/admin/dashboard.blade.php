<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    @include('admin.css')
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 h-full">
    @include('admin.header')
    <div class="flex">
        @include('admin.sidebar')

        <div class="flex-1 min-h-screen">
            <div class="p-6">
                <div class="container mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white pb-4 mb-6 border-b border-gray-200 dark:border-gray-700">
                        Panel de Control
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Tarjeta de Usuarios -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:-translate-y-2">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Usuarios</div>
                            <div class="text-4xl font-bold text-blue-600 dark:text-blue-500 my-3">{{ $stats['total_users'] }}</div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Usuarios registrados</span>
                                <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-500"></i>
                            </div>
                        </div>

                        <!-- Tarjeta de Libros -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:-translate-y-2">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Libros</div>
                            <div class="text-4xl font-bold text-green-600 dark:text-green-500 my-3">{{ $stats['total_books'] }}</div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Biblioteca completa</span>
                                <i class="fas fa-book text-2xl text-green-600 dark:text-green-500"></i>
                            </div>
                        </div>

                        <!-- Tarjeta de Préstamos -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:-translate-y-2">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Préstamos Activos</div>
                            <div class="text-4xl font-bold text-yellow-600 dark:text-yellow-500 my-3">{{ $stats['active_borrows'] }}</div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">En circulación</span>
                                <i class="fas fa-bookmark text-2xl text-yellow-600 dark:text-yellow-500"></i>
                            </div>
                        </div>

                        <!-- Tarjeta de Devoluciones -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:-translate-y-2">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Libros Devueltos</div>
                            <div class="text-4xl font-bold text-cyan-600 dark:text-cyan-500 my-3">{{ $stats['returned_books'] }}</div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Completados</span>
                                <i class="fas fa-check-circle text-2xl text-cyan-600 dark:text-cyan-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')
    @include('ai.chat-button')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    @stack('scripts')
</body>
</html>
