<!DOCTYPE html>
<html class="h-full">
<head>
    @include('admin.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        // Asegurarnos que el modo oscuro se aplica correctamente
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-full transition-colors duration-300">
    @include('admin.header')
    <div class="flex">
        @include('admin.sidebar')

        <div class="w-full p-5 lg:p-8 mt-20">
            <div class="w-full max-w-7xl mx-auto">
                <!-- Contenido de la página de backups -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Gestión de Backups de Base de Datos</h1>
                        <form action="{{ route('backups.create') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600 px-4 py-2 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg inline-flex items-center">
                                <i class="fas fa-database mr-2"></i>Crear Nuevo Backup
                            </button>
                        </form>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="py-3 px-4 text-left">Archivo</th>
                                    <th class="py-3 px-4 text-left">Tamaño</th>
                                    <th class="py-3 px-4 text-left">Fecha de creación</th>
                                    <th class="py-3 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($backups as $backup)
                                    <tr class="border-t dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $backup['file_name'] }}</td>
                                        <td class="py-3 px-4">{{ round($backup['file_size'] / 1024 / 1024, 2) }} MB</td>
                                        <td class="py-3 px-4">{{ date('d/m/Y H:i:s', $backup['created_at']) }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('backups.download', ['fileName' => $backup['file_name']]) }}"
                                                   class="bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600 px-3 py-1.5 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg inline-flex items-center text-sm">
                                                    <i class="fas fa-download mr-2"></i>Descargar
                                                </a>
                                                <form action="{{ route('backups.destroy', ['fileName' => $backup['file_name']]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-gradient-to-r from-red-500 via-white to-red-500 hover:from-red-600 hover:to-red-600 text-red-600 px-3 py-1.5 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg inline-flex items-center text-sm"
                                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este backup?')">
                                                        <i class="fas fa-trash-can mr-2"></i>Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center">No hay backups disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>