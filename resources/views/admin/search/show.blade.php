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
    <title>Detalles del Préstamo</title>
    @include('admin.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200">
    @include('admin.header')
    
    <div class="max-w-3xl mx-auto px-4 py-10">
        <h1 class="text-3xl md:text-4xl font-bold text-center mb-8 text-gray-800 dark:text-white">Detalles del Préstamo</h1>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Cabecera de la tarjeta -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-500 dark:from-blue-700 dark:to-indigo-600 px-6 py-4">
                <h2 class="text-xl md:text-2xl font-bold text-white">{{ $loan->book->title }}</h2>
            </div>
            
            <!-- Cuerpo de la tarjeta -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Nombre del Solicitante -->
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 border-l-4 border-blue-500">
                        <p class="text-sm text-blue-600 dark:text-blue-300 font-medium mb-1">Nombre del Solicitante</p>
                        <p class="text-lg font-semibold">{{ $loan->borrower_name }}</p>
                    </div>
                    
                    <!-- Número de Identificación -->
                    <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-4 border-l-4 border-purple-500">
                        <p class="text-sm text-purple-600 dark:text-purple-300 font-medium mb-1">Número de Identificación</p>
                        <p class="text-lg font-semibold">{{ $loan->borrower_id }}</p>
                    </div>
                    
                    <!-- Cantidad de Hojas -->
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 border-l-4 border-blue-500">
                        <p class="text-sm text-blue-600 dark:text-blue-300 font-medium mb-1">Cantidad de Hojas</p>
                        <p class="text-lg font-semibold">{{ $loan->page_count }}</p>
                    </div>
                    
                    <!-- Estado -->
                    <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-4 border-l-4 border-purple-500">
                        <p class="text-sm text-purple-600 dark:text-purple-300 font-medium mb-1">Estado</p>
                        <p class="text-lg font-semibold">
                            @if($loan->is_borrowed)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Prestado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Disponible
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-center space-x-4 mt-6">
                    <a href="{{ url()->previous() }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Regresar
                    </a>
                    
                    <a href="{{ route('loan.edit', $loan->book_id) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Préstamo
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @include('admin.footer')
</body>
</html>
