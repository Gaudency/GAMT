<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestar Carpeta</title>
    @include('admin.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200">
    @include('admin.header')
    
    <div class="max-w-3xl mx-auto px-4 py-10">
        <h1 class="text-3xl md:text-4xl font-bold text-center mb-8 text-gray-800 dark:text-white">Prestar Carpeta</h1>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">
            <form action="{{ route('loan.store', $book->id) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="borrower_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Solicitante
                    </label>
                    <input type="text" 
                           name="borrower_name" 
                           id="borrower_name" 
                           required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                </div>
                
                <div class="mb-6">
                    <label for="borrower_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Número de Identificación
                    </label>
                    <input type="text" 
                           name="borrower_id" 
                           id="borrower_id" 
                           required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                </div>
                
                <div class="mb-8">
                    <label for="page_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cantidad de Hojas
                    </label>
                    <input type="number" 
                           name="page_count" 
                           id="page_count" 
                           value="{{ $book->page_count }}" 
                           readonly
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200 cursor-not-allowed">
                </div>
                
                <div class="flex justify-center">
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                        Prestar Carpeta
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @include('admin.footer')
</body>
</html>
