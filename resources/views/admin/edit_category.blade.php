<!DOCTYPE html>
<html class="h-full">
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

    <div class="flex">
        @include('admin.sidebar')  
        
        <div class="w-full p-5 lg:p-8 mt-20"> <!-- mt-20 para compensar el header fijo -->
            <div class="w-full max-w-7xl mx-auto">
                <div class="text-center py-8 px-4 w-full mx-auto">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-10 transition-colors duration-300">Actualizar Categoría</h2>
                    
                    <div class="max-w-lg mx-auto bg-white/95 dark:bg-gray-800/95 rounded-lg shadow-lg p-8 transition-colors duration-300 backdrop-blur-sm">
                        @if(session()->has('message'))
                            <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6 rounded shadow-md relative" role="alert">
                                <span>{{ session()->get('message') }}</span>
                                <button type="button" class="absolute top-0 right-0 mt-4 mr-4 text-green-700 dark:text-green-200 hover:text-green-900 dark:hover:text-green-100 transition-colors duration-300" onclick="this.parentElement.remove()">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                        
                        <form action="{{ url('/update_category', $category->id) }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="cat_title" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Título de la Categoría</label>
                                <input type="text" id="cat_title" name="cat_title" value="{{ $category->cat_title }}" 
                                    class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition-colors duration-300"
                                    required>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ url('category_page') }}" 
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-1 transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Volver
                                </a>
                                
                                <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-1 transition-all duration-300">
                                    Actualizar Categoría
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.getElementById('themeToggle').addEventListener('click', function() {
            toggleDarkMode();
        });
    </script>
</body>
</html>


