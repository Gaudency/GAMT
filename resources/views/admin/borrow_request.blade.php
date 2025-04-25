<!DOCTYPE html>
<html lang="es" class="h-full">
<script>
    // Asegurarnos que el modo oscuro se aplica correctamente
    if (localStorage.getItem('darkMode') === 'true' ||
        (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Préstamo</title>
    @include('admin.css')
    <style>
        /* Efecto de brillo para los botones */
        .shine-button {
            position: relative;
            overflow: hidden;
        }

        .shine-button:after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: rotate(30deg);
            opacity: 0;
            transition: opacity 0.6s;
        }

        .shine-button:hover:after {
            opacity: 1;
            animation: shine 1.5s;
        }

        @keyframes shine {
            0% { transform: translateX(-200%) rotate(30deg); }
            100% { transform: translateX(200%) rotate(30deg); }
        }

        /* Animación para los degradados */
        .bg-gradient-to-r {
            background-size: 200% 200%;
            animation: gradientMove 5s ease infinite;
        }

        @keyframes gradientMove {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">
    @include('admin.header')
    @include('admin.sidebar')
    <!-- Contenido principal con padding para respetar el header -->
    <div class="pt-16 md:pt-20 px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold text-center mb-8 text-gray-800 dark:text-white relative pb-3">
                Solicitudes de Préstamo
                <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-blue-500 rounded"></span>
            </h1>

            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <!-- Total solicitudes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center border border-gray-200 dark:border-gray-700 transition duration-300 hover:shadow-lg">
                    <div class="text-3xl font-bold text-gray-700 dark:text-gray-300">{{ $stats['total'] }}</div>
                    <div class="text-xs uppercase tracking-wider mt-1 font-medium text-gray-500 dark:text-gray-400">Total Solicitudes</div>
                </div>

                <!-- Pendientes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center border border-gray-200 dark:border-gray-700 transition duration-300 hover:shadow-lg">
                    <div class="text-3xl font-bold text-amber-500 dark:text-amber-400">{{ $stats['pending'] }}</div>
                    <div class="text-xs uppercase tracking-wider mt-1 font-medium text-gray-500 dark:text-gray-400">Pendientes</div>
                </div>

                <!-- Aprobadas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center border border-gray-200 dark:border-gray-700 transition duration-300 hover:shadow-lg">
                    <div class="text-3xl font-bold text-green-500 dark:text-green-400">{{ $stats['approved'] }}</div>
                    <div class="text-xs uppercase tracking-wider mt-1 font-medium text-gray-500 dark:text-gray-400">Aprobadas</div>
                </div>

                <!-- Devueltas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center border border-gray-200 dark:border-gray-700 transition duration-300 hover:shadow-lg">
                    <div class="text-3xl font-bold text-blue-500 dark:text-blue-400">{{ $stats['returned'] }}</div>
                    <div class="text-xs uppercase tracking-wider mt-1 font-medium text-gray-500 dark:text-gray-400">Devueltas</div>
                </div>

                <!-- Rechazadas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center border border-gray-200 dark:border-gray-700 transition duration-300 hover:shadow-lg">
                    <div class="text-3xl font-bold text-red-500 dark:text-red-400">{{ $stats['rejected'] }}</div>
                    <div class="text-xs uppercase tracking-wider mt-1 font-medium text-gray-500 dark:text-gray-400">Rechazadas</div>
                </div>
            </div>

            <!-- Tabla de solicitudes -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Foto de Perfil
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Teléfono
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Título Carpeta
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Año
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Imagen
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Chat
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($data as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{$item->user->name}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img class="h-10 w-10 rounded-full object-cover"
                                         src="{{ $item->user->profile_photo_url ?? 'default_profile.jpg' }}"
                                         alt="Foto de perfil">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{$item->user->phone}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{$item->book->title}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{$item->book->tomo}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if(strtolower($item->status) == 'applied')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Pendiente
                                        </span>
                                    @elseif(strtolower($item->status) == 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                            Aprobado
                                        </span>
                                    @elseif(strtolower($item->status) == 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Rechazado
                                        </span>
                                    @elseif(strtolower($item->status) == 'returned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Devuelto
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <img class="h-32 w-20 object-cover rounded-lg shadow-md transform transition-transform duration-300 hover:scale-110"
                                         src="book/{{$item->book->book_img}}"
                                         alt="Imagen carpeta">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('chat.show', $item->user_id) }}?borrow_id={{$item->id}}"
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-400/80 to-purple-500/80 hover:from-blue-500 hover:to-purple-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shine-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        Chat
                                        @if($item->unread_messages_count > 0)
                                            <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                                {{ $item->unread_messages_count }}
                                            </span>
                                        @endif
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2">
                                    <!-- BOTÓN PRESTADO - MODERNIZADO -->
                                    <a href="{{url('approve_book',$item->id)}}"
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-amber-500 via-white to-amber-500 hover:from-amber-600 hover:to-amber-600 text-amber-600 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shine-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aprobar
                                    </a>

                                    <!-- BOTÓN CON PROBLEMAS - MODERNIZADO -->
                                    <a href="{{url('rejected_book',$item->id)}}"
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 via-white to-red-500 hover:from-red-600 hover:to-red-600 text-red-600 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shine-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Rechazar
                                    </a>

                                    <!-- BOTÓN DEVUELTO - MODERNIZADO -->
                                    <a href="{{url('return_book',$item->id)}}"
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-500 via-white to-green-500 hover:from-green-600 hover:to-green-600 text-green-600 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shine-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Devolver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')

    <!-- FontAwesome para íconos (opcional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" defer></script>

    <!-- Script para aplicar efecto shine a los botones -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar efecto shine en los botones
        document.querySelectorAll('.shine-button').forEach(element => {
            element.classList.add('shine-button');
        });
    });
    </script>
</body>
</html>
