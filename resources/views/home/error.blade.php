<!DOCTYPE html>
<html lang="es" class="dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Sistema de Gestión Documental</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {} }
        }
    </script>
    
    <!-- Inicialización del tema oscuro/claro -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-violet-900 to-indigo-900 text-white min-h-screen flex items-center justify-center">
    <div class="container max-w-md mx-auto px-4 py-10">
        <div class="bg-white/10 backdrop-blur-md rounded-xl overflow-hidden shadow-lg p-8 text-center">
            <div class="text-5xl text-red-300 mb-6">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            
            <h1 class="text-2xl font-bold mb-4">{{ $message ?? 'Ha ocurrido un error' }}</h1>
            
            <p class="text-gray-300 mb-8">
                Por favor, intenta nuevamente o comunícate con el administrador del sistema.
            </p>
            
            <div class="flex flex-col space-y-3">
                <a href="{{ url('/') }}" class="px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-home mr-2"></i> Volver al inicio
                </a>
                
                @if(config('app.debug'))
                <div class="mt-8 p-4 bg-red-900/50 rounded-lg text-left">
                    <p class="text-sm text-gray-300 mb-2">Información de depuración:</p>
                    <code class="text-xs text-red-300 whitespace-pre-wrap">
                        {{ $debugInfo ?? 'No hay información adicional disponible' }}
                    </code>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html> 