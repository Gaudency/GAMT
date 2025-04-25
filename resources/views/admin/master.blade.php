<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Préstamos</title>

    <!-- Script para modo oscuro (colocado en el head para ejecutarse antes de renderizar) -->
    <script>
        // Asegurarnos que el modo oscuro se aplica correctamente
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @include('admin.css')
    @stack('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col">
    @include('admin.header')

    <div class="flex flex-1">
        <!-- Sidebar -->
        <!-- Contenido principal -->
        <div class="flex-1 overflow-x-hidden p-4 md:p-6">
            @yield('content')
        </div>
    </div>

    @include('admin.footer')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
</body>
</html>
