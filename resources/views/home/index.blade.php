<!DOCTYPE html>
<html lang="es" class="dark:bg-gray-900">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sistema de Gestión Documental - GAMT</title>
    <meta name="description" content="Sistema de gestión y préstamo de carpetas documentales" />

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
              }
            },
          },
        }
      }
    </script>

    <!-- Inicializar tema oscuro/claro -->
    <script>
      if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    </script>

    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap");

      html {
        font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      }

      body {
        background-image: url('/images/header.png');
      }
    </style>
  </head>

  <body class="leading-normal tracking-normal text-red-400 dark:text-red-300 m-6 bg-cover bg-fixed transition-colors">
    <div class="h-full">
      <!--Nav-->
      <div class="w-full container mx-auto">
        <div class="w-full flex items-center justify-between">
          <a class="flex items-center text-red-600 dark:text-red-400 no-underline hover:no-underline font-bold text-2xl lg:text-4xl" href="#">
            GAMT<span class="bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-red-600 to-red-700">Docs</span>
          </a>

          <div class="flex items-center justify-end space-x-4">
            <!-- Toggle de tema oscuro/claro -->
            <button id="theme-toggle" class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
              <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
              </svg>
              <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
              </svg>
            </button>

            <!-- Enlaces de navegación -->
            @auth
              <a href="{{ route('explore') }}" class="inline-block text-red-300 no-underline hover:text-red-500 hover:underline px-4 py-2 rounded-lg bg-white/10 backdrop-blur-sm">
                Explorar
              </a>
              <a href="{{ route('books.history') }}" class="inline-block text-red-300 no-underline hover:text-red-500 hover:underline px-4 py-2 rounded-lg bg-white/10 backdrop-blur-sm">
                Mi Historial
              </a>
              <a href="{{ route('perfil.show') }}" class="inline-block text-red-300 no-underline hover:text-red-500 hover:underline px-4 py-2 rounded-lg bg-white/10 backdrop-blur-sm">
                Mi Perfil
              </a>
            @else
              <a href="{{ route('login') }}" class="inline-block text-red-300 no-underline hover:text-red-500 hover:underline px-4 py-2 rounded-lg bg-white/10 backdrop-blur-sm">
                Iniciar Sesión
              </a>
              <a href="{{ route('register') }}" class="inline-block text-red-300 no-underline hover:text-red-500 hover:underline px-4 py-2 rounded-lg bg-white/10 backdrop-blur-sm">
                Registrarse
              </a>
            @endauth
          </div>
        </div>
      </div>

      <!--Main-->
      <div class="container pt-24 md:pt-36 mx-auto flex flex-wrap flex-col md:flex-row items-center">
        <!--Left Col-->
        <div class="flex flex-col w-full xl:w-2/5 justify-center lg:items-start overflow-y-hidden">
          <h1 class="my-4 text-3xl md:text-5xl text-white opacity-90 font-bold leading-tight text-center md:text-left">
            Sistema de
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-red-600 to-red-700">
              Gestión Documental
            </span>
            GAMT
          </h1>
          <p class="leading-normal text-base md:text-xl mb-8 text-center md:text-left text-white/80">
            Accede, gestiona y solicita préstamos de carpetas y documentos oficiales de manera rápida y segura.
          </p>

          @auth
          <!-- Si el usuario está autenticado fgc-->
          <div class="bg-gray-900/75 dark:bg-gray-800/75 w-full shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4 backdrop-blur-sm">
            <div class="mb-6">
              <h3 class="text-xl font-bold text-red-300 mb-4">Bienvenido(a), {{ Auth::user()->name }}</h3>
              <p class="text-blue-300 dark:text-blue-200 mb-4">¿Qué deseas hacer hoy?</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <a href="{{ route('explore') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-4 rounded text-center transform transition hover:scale-105 duration-300 ease-in-out">
                <i class="fas fa-search mr-2"></i> Explorar Carpetas
              </a>
              <a href="{{ route('books.history') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-4 rounded text-center transform transition hover:scale-105 duration-300 ease-in-out">
                <i class="fas fa-history mr-2"></i> Ver Mi Historial
              </a>

              <a href="{{ route('perfil.show') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-bold py-3 px-4 rounded text-center transform transition hover:scale-105 duration-300 ease-in-out">
                <i class="fas fa-user mr-2"></i> Mi Perfil
              </a>
            </div>
          </div>
          @else
          <!-- Si el usuario no está autenticado -->
          <form action="{{ route('login') }}" method="GET" class="bg-gray-900/75 dark:bg-gray-800/75 w-full shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4 backdrop-blur-sm">
            <div class="mb-4">
              <h3 class="block text-blue-300 dark:text-blue-200 text-xl font-bold mb-4">
                Accede a tu cuenta
              </h3>
              <p class="text-white/70 mb-6">
                Inicia sesión para acceder a todas las funcionalidades del sistema.
              </p>
            </div>

            <div class="flex flex-col space-y-4">
              <a href="{{ route('login') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-6 rounded text-center transform transition hover:scale-105 duration-300 ease-in-out">
                Iniciar Sesión
              </a>
              <a href="{{ route('register') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold py-3 px-6 rounded text-center transform transition hover:scale-105 duration-300 ease-in-out backdrop-blur-sm">
                Crear Cuenta
              </a>
            </div>
          </form>
          @endauth
        </div>

        <!--Right Col-->
        <div class="w-full xl:w-3/5 p-12 overflow-hidden">
          <img class="mx-auto w-full md:w-4/5 transform transition hover:scale-105 duration-700 ease-in-out" src="{{ asset('images/documents.svg') }}" alt="Documentos" />
        </div>

        <!-- Categorías destacadas -->
        <div class="w-full text-center pt-10 pb-6">
          <h2 class="text-2xl font-bold text-white/90 mb-6">Categorías destacadas</h2>
          <div class="flex flex-wrap justify-center gap-4">
            @forelse($categories as $category)
              <a href="{{ route('user.category.search', $category->id) }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white px-6 py-3 rounded-lg transform transition hover:scale-105 duration-300 ease-in-out">
                {{ $category->cat_title }}
              </a>
            @empty
              <p class="text-gray-300">No hay categorías disponibles actualmente</p>
            @endforelse
          </div>
        </div>

        <!-- Últimas carpetas añadidas -->
        @if(isset($data) && count($data) > 0)
        <div class="w-full pt-16">
          <h2 class="text-2xl font-bold text-white/90 text-center mb-8">Últimas carpetas añadidas</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($data->take(4) as $book)
            <div class="bg-white/10 backdrop-blur-sm dark:bg-gray-800/50 rounded-lg shadow-lg overflow-hidden transform transition hover:scale-105 duration-300 ease-in-out">
              @if(isset($book->book_img))
              <img src="{{ asset('book/' . $book->book_img) }}" alt="{{ $book->title ?? 'Carpeta' }}" class="w-full h-40 object-cover">
              @else
              <div class="w-full h-40 bg-gray-700 flex items-center justify-center">
                <i class="fas fa-folder-open text-4xl text-gray-400"></i>
              </div>
              @endif
              <div class="p-4">
                <h3 class="font-bold text-white truncate">{{ $book->title ?? 'Sin título' }}</h3>
                <p class="text-white/70 text-sm">{{ $book->year ?? 'Sin año' }}</p>
                <div class="mt-4 flex justify-center">
                  <a href="{{ route('books.details', $book->id) }}" class="bg-red-600/80 hover:bg-red-700 text-white px-4 py-2 rounded-md w-full text-center">
                    Ver detalles
                  </a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        <!--Footer-->
        <div class="w-full pt-16 pb-6 text-sm text-center text-white/60">
          <p>&copy; {{ date('Y') }} Sistema de Gestión Documental - GAMT</p>
          <p class="mt-2">Desarrollado para optimizar la gestión de documentos institucionales</p>

          <!-- Redes Sociales -->
          <div class="flex justify-center space-x-6 items-center mt-4">
            <!-- Sitio Web -->
            <a href="https://gamtomave.gob.bo/" class="text-blue-400 hover:text-blue-300 transition-colors transform hover:scale-110 duration-300">
              <i class="fas fa-globe text-xl"></i>
            </a>

            <!-- Facebook -->
            <a href="https://www.facebook.com/GAMTOMAVE2025" class="text-blue-500 hover:text-blue-400 transition-colors transform hover:scale-110 duration-300">
              <i class="fab fa-facebook-f text-xl"></i>
            </a>

            <!-- Instagram -->
            <a href="#" class="text-pink-400 hover:text-pink-300 transition-colors transform hover:scale-110 duration-300">
              <i class="fab fa-instagram text-xl"></i>
            </a>

            <!-- X (Twitter) -->
            <a href="#" class="text-white hover:text-gray-200 transition-colors transform hover:scale-110 duration-300">
              <span class="font-bold text-xl">X</span>
            </a>

            <!-- WhatsApp -->
            <a href="https://wa.me/59167900876" class="text-green-400 hover:text-green-300 transition-colors transform hover:scale-110 duration-300">
              <i class="fab fa-whatsapp text-xl"></i>
            </a>

            <!-- TikTok -->
            <a href="http://www.tiktok.com/@gam_tomave" class="text-white hover:text-gray-200 transition-colors transform hover:scale-110 duration-300">
              <i class="fab fa-tiktok text-xl"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Script para el toggle del tema -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
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
  </body>
</html>
