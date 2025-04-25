<!DOCTYPE html>
<html lang="es">
<head>
    @include('admin.css')
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
    <div class="flex mt-16">
        @include('admin.sidebar')

        <div class="flex-1 p-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="text-center py-6">
                    <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-4 rounded-lg inline-block mb-6">
                        <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">¡Préstamo Registrado Exitosamente!</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        Se ha registrado correctamente el préstamo de <span class="font-semibold">{{ $loan->book_title }}</span>
                    </p>
                </div>

                <div class="max-w-2xl mx-auto bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Detalles del Préstamo</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Código de Carpeta</p>
                            <p class="font-medium">{{ $loan->folder_code }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Título del Libro</p>
                            <p class="font-medium">{{ $loan->book_title }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad de Hojas</p>
                            <p class="font-medium">{{ $loan->sheets_count }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Prestador</p>
                            <p class="font-medium">{{ $loan->lender_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Préstamo</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Devolución</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($loan->digital_signature)
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Firma Digital</p>
                        <img src="{{ $loan->digital_signature }}" alt="Firma digital" class="max-w-full h-auto border border-gray-300 dark:border-gray-600 rounded-md">
                    </div>
                    @endif
                </div>

                <div class="flex justify-center mt-8 space-x-4">
                    <a href="{{ route('loose-loans.index') }}" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition duration-300">
                        Volver a la Lista
                    </a>
                    <a href="{{ route('loose-loans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        Registrar Otro Préstamo
                    </a>
                    <a href="{{ route('loose-loans.show', $loan->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')
</body>
</html>

