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
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalles del Préstamo</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('loose-loans.index') }}" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition duration-300">
                            Volver
                        </a>
                        <a href="{{ route('loose-loans.edit', $loan->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                            Editar
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Información del Préstamo</h2>

                        <div class="space-y-3">
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
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Fechas y Estado</h2>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Préstamo</p>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i') }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Devolución</p>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y H:i') }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Estado</p>
                                <p class="font-medium">
                                    @if($loan->status == 'loaned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Prestado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Devuelto
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Registro</p>
                                <p class="font-medium">{{ $loan->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Descripción</h2>
                    <p>{{ $loan->description ?? 'Sin descripción' }}</p>
                </div>

                <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Firma Digital</h2>
                    @if($loan->digital_signature)
                        <img src="{{ $loan->digital_signature }}" alt="Firma digital" class="max-w-full h-auto border border-gray-300 dark:border-gray-600 rounded-md">
                    @else
                        <p>No hay firma digital registrada.</p>
                    @endif
                </div>

                @if($loan->status == 'loaned')
                <div class="mt-6 flex justify-center">
                    <form method="POST" action="{{ route('loose-loans.return', $loan->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                            Marcar como Devuelto
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.footer')
</body>
</html>

