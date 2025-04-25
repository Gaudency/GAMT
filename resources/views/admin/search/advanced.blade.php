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
    <title>Buscador ADMIN</title>
    @include('admin.css')
    <style>
        /* Estilos generales - solo mantenemos lo básico */
        select option {
            padding: 8px;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200">
    @include('admin.header')

    <!-- Estructura principal con margen superior para respetar el header -->
    <div class="pt-16 md:pt-20">
        <div class="flex">
            @include('admin.sidebar')

            <div class="flex-1 p-6 overflow-y-auto">
                <h1 class="text-center py-6 text-4xl font-bold text-gray-800 dark:text-white">Buscador Avanzado de Carpetas</h1>

                <!-- Formulario de búsqueda avanzada -->
                <form action="{{ route('searchh.filter') }}" method="GET" class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <!-- Búsqueda general -->
                        <div class="col-span-full">
                            <input type="text" name="searchh"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Buscar en todos los campos"
                                   value="{{ request()->input('searchh') }}">
                        </div>

                        <!-- Campos específicos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código</label>
                            <input type="text" name="codigo"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Código digital"
                                   value="{{ request()->input('codigo') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                            <input type="text" name="title"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Título de la carpeta"
                                   value="{{ request()->input('title') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ubicación</label>
                            <input type="text" name="ubicacion"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Ubicación fisíca"
                                   value="{{ request()->input('ubicacion') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Año</label>
                            <input type="number" name="year"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="desde 1990 a presente"
                                   value="{{ request()->input('year') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Codigo</label>
                            <input type="text" name="tomo"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="codigo fisico"
                                   value="{{ request()->input('tomo') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                            <select name="estado"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="Prestado" {{ request()->input('estado') == 'Prestado' ? 'selected' : '' }}>Prestado</option>
                                <option value="No Prestado" {{ request()->input('estado') == 'No Prestado' ? 'selected' : '' }}>No Prestado</option>
                            </select>
                        </div>

                        <input type="hidden" name="category_id" value="">
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-between">
                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-red-500 via-white to-red-500 hover:from-red-600 hover:via-gray-100 hover:to-red-600 text-gray-900 font-medium rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                            Buscar
                        </button>

                        <a href="{{ route('search.advanced') }}"
                           class="px-6 py-2 bg-gradient-to-r from-red-500 via-white to-red-500 hover:from-red-600 hover:via-gray-100 hover:to-red-600 text-gray-900 font-medium rounded-lg transition duration-200">
                            Limpiar filtros
                        </a>
                    </div>
                </form>

                <!-- Navegación de categorías -->
                <div class="mb-8 overflow-x-auto">
                    <ul class="flex flex-wrap justify-center gap-2 md:gap-4">
                        <li>
                            <a href="{{ url('advanced') }}"
                               class="px-4 py-2 block rounded-full {{ !request()->segment(1) || request()->segment(1) === 'advanced' ? 'bg-gradient-to-r from-red-500 via-white to-red-500 text-gray-900 hover:from-red-600 hover:via-gray-100 hover:to-red-600' : 'bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-500' }} transition duration-200 shadow-sm">
                                Todas Carpetas
                            </a>
                        </li>
                        @foreach($category as $category)
                        <li>
                            <a href="{{ url('cat_search', $category->id) }}"
                               class="px-4 py-2 block rounded-full {{ request()->segment(1) === 'cat_search' && request()->segment(2) == $category->id ? 'bg-gradient-to-r from-red-500 via-white to-red-500 text-gray-900 hover:from-red-600 hover:via-gray-100 hover:to-red-600' : 'bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-500' }} transition duration-200 shadow-sm">
                                {{$category->cat_title}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Grid de carpetas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($data as $book)
                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 border border-gray-200 dark:border-gray-700">
                        <!-- Imagen con efecto hover -->
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('book/' . $book->book_img) }}"
                                 class="w-full h-full object-cover transition duration-300 transform hover:scale-110 hover:border-2 hover:border-white dark:hover:border-gray-200"
                                 alt="{{ $book->title }}">
                        </div>

                        <div class="p-4">
                            <h5 class="text-lg font-bold mb-2 text-gray-800 dark:text-white">{{ $book->title }}</h5>
                            <div class="flex items-center mb-2">
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    N_codigo <span class="font-semibold">{{ $book->N_codigo }}</span>
                                </p>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                                AÑO <span class="font-semibold">{{ $book->year }}</span>
                            </p>
                            <a href="{{ url('details', $book->id) }}"
                               class="block w-full text-center px-4 py-2 bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:via-gray-100 hover:to-blue-600 text-gray-900 font-medium rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-md">
                                Más detalles
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación (si existe) -->
                @if(isset($data) && method_exists($data, 'links'))
                <div class="mt-8">
                    {{ $data->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.footer')

    <!-- FontAwesome para íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
