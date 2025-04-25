<!DOCTYPE html>
<html lang="es" class="dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión Documental - GAMT')</title>
    <meta name="description" content="@yield('description', 'Sistema de gestión y préstamo de carpetas documentales')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              primary: {
                light: '#dc3545',
                dark: '#ff6b7d',
              },
              violet: {
                500: '#8B5CF6',
                600: '#7C3AED',
                700: '#6D28D9',
                800: '#5B21B6',
              }
            },
          },
        }
      }
    </script>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome (Global) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Inicializar tema oscuro/claro -->
    <script>
      if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    </script>

    <!-- Google Fonts - Poppins -->
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

      html {
        font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      }
    </style>

    <!-- Estilos adicionales -->
    @yield('styles')
    @yield('meta')
</head>
<body class="@yield('body-class', 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen flex flex-col transition-colors')">
    <!-- Header (si lo necesitas en todas las páginas) -->
    @yield('header')

    <!-- Contenido principal -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer (si lo necesitas en todas las páginas) -->
    @yield('footer')

    <!-- Incluir el chat con IA -->
    @include('ai.chat-button')

    <!-- Script para el toggle del tema (presente en todas las páginas) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
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
            }
        });
    </script>

    <!-- Scripts adicionales -->
    @yield('scripts')
</body>
</html>
