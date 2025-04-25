<!DOCTYPE html>
<html lang="en" class="light">
<head>
   <script src="https://cdn.tailwindcss.com"></script>
   <script>
    // Configuración de Tailwind para modo oscuro
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: {
              light: '#1E40AF',
              dark: '#3B82F6'
            }
          }
        }
      }
    }
   </script>
   <style type="text/css">
    /* Estilos base que se aplicarán independientemente del modo */
    .div_center {
      text-align: center;
      margin: auto;
    }

    /* Eliminamos estilos específicos que serán reemplazados por Tailwind */
   </style>
</head>
<body class="transition-colors duration-300 dark:bg-gray-900 bg-gray-100">

    <div class="d-flex align-items-stretch">
        <!-- Sidebar Navigation-->
        @include('admin.sidebar')
        <!-- Sidebar Navigation end-->
        <div class="page-content">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="div_center p-4">
                        <!-- Botón para cambiar entre modo claro y oscuro -->
                        <div class="flex justify-end mb-4">
                            <button id="theme-toggle" class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                                <span class="dark:hidden"><i class="fas fa-moon"></i> Modo Oscuro</span>
                                <span class="hidden dark:inline"><i class="fas fa-sun"></i> Modo Claro</span>
                            </button>
                        </div>

                        <h1 class="text-4xl font-bold mb-8 text-gray-800 dark:text-white">REPORTES</h1>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Reporte de Carpetas - ACTUALIZADO CON DEGRADADO -->
                            <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-lg shadow-md p-6 border border-orange-200 dark:border-orange-800">
                                <h2 class="text-xl font-semibold mb-4 text-center text-orange-700 dark:text-orange-400">
                                    <i class="fas fa-folder-open mr-2"></i>Reporte de Carpetas
                                </h2>

                                <p class="text-gray-600 dark:text-gray-300 mb-4 text-center">
                                    Genera un reporte detallado de todas las carpetas digitalizadas y disponibles en el sistema.
                                </p>

                                <a href="{{ route('reporte.libros') }}" target="_blank" class="block w-full bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-medium py-3 px-4 rounded text-center mb-4 transition duration-300 shadow-md hover:shadow-lg">
                                    <i class="fas fa-file-pdf mr-2"></i> Generar total
                                </a>

                                <div class="mt-4">
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Filtrar carpetas por categoría:</label>
                                    <form action="{{ route('reporte.libros') }}" method="GET" target="_blank" class="flex items-center">
                                        <select name="categoria" class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md py-2 px-3">
                                            <option value="">Todas las categorías</option>
                                            @foreach(\App\Models\Category::orderBy('cat_title')->get() as $cat)
                                                <option value="{{ $cat->cat_title }}">{{ $cat->cat_title }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="ml-2 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white py-2 px-4 rounded flex items-center justify-center shadow-md hover:shadow-lg">
                                            <i class="fas fa-filter mr-2"></i> Filtrar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Reporte de Préstamos - ACTUALIZADO CON DEGRADADO -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg shadow-md p-6 border border-blue-200 dark:border-blue-800">
                                <h2 class="text-xl font-semibold mb-4 text-center text-blue-700 dark:text-blue-400">
                                    <i class="fas fa-clipboard-list mr-2"></i>Reporte de Préstamos en Línea
                                </h2>

                                <p class="text-gray-600 dark:text-gray-300 mb-4 text-center">
                                    Obtén un informe completo de todas las solicitudes de préstamos realizadas en línea.
                                </p>

                                <a href="{{ route('reporte.prestamos') }}" target="_blank" class="block w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium py-3 px-4 rounded text-center transition duration-300 shadow-md hover:shadow-lg">
                                    <i class="fas fa-file-pdf mr-2"></i> Generar Reporte
                                </a>
                            </div>

                            <!-- Reporte de Comprobantes con tema adaptable -->
                            <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-lg shadow-md p-6 border border-red-200 dark:border-red-800">
                                <h2 class="text-xl font-semibold mb-4 text-center text-red-800 dark:text-red-400">
                                    <i class="fas fa-file-invoice mr-2"></i>Reporte de prestamos generales y Comprobantes
                                </h2>

                                <form action="{{ route('reporte.documentos') }}" method="GET" target="_blank" class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="form-group">
                                            <label for="mes" class="block text-gray-700 dark:text-gray-300 mb-1">Mes:</label>
                                            <select name="mes" id="mes" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                                <option value="">Todos los meses</option>
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="anio" class="block text-gray-700 dark:text-gray-300 mb-1">Año:</label>
                                            <select name="anio" id="anio" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                                <option value="">Todos los años</option>
                                                @for($i = 2024; $i <= 2030; $i++)
                                                    <option value="{{ $i }}" {{ $i == 2025 ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-3 px-4 rounded transition duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                                        <i class="fas fa-file-export mr-2"></i> Generar Reporte
                                    </button>
                                </form>
                            </div>

                            <!-- Reporte de Usuarios - ACTUALIZADO CON DEGRADADO -->
                            <div class="bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-lg shadow-md p-6 border border-purple-200 dark:border-purple-800">
                                <h2 class="text-xl font-semibold mb-4 text-center text-purple-800 dark:text-purple-400">
                                    <i class="fas fa-users mr-2"></i>Reporte de Usuarios
                                </h2>

                                <p class="text-gray-600 dark:text-gray-300 mb-4 text-center">
                                    Genera un reporte detallado de todos los usuarios registrados en el sistema.
                                </p>

                                <a href="{{ route('reporte.usuarios') }}" target="_blank" class="block w-full bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 text-white font-medium py-3 px-4 rounded text-center transition duration-300 shadow-md hover:shadow-lg">
                                    <i class="fas fa-user-friends mr-2"></i> Generar Reporte
                                </a>
                            </div>

                            <!-- NUEVO: Reporte de Préstamos Sueltos -->
                            <div class="bg-gradient-to-r from-red-50 to-blue-50 dark:from-red-900/20 dark:to-blue-900/20 rounded-lg shadow-md p-6 border border-red-200 dark:border-red-800">
                                <h2 class="text-xl font-semibold mb-4 text-center text-red-800 dark:text-red-400">
                                    <i class="fas fa-file-signature mr-2"></i>Reporte de Préstamos Sueltos
                                </h2>

                                <form action="{{ route('reports.prestamos-libres') }}" method="GET" target="_blank" class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="form-group">
                                            <label for="mes_pl" class="block text-gray-700 dark:text-gray-300 mb-1">Mes:</label>
                                            <select name="mes" id="mes_pl" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                                <option value="">Todos los meses</option>
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="anio_pl" class="block text-gray-700 dark:text-gray-300 mb-1">Año:</label>
                                            <select name="anio" id="anio_pl" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                                <option value="">Todos los años</option>
                                                @for($i = 2024; $i <= 2030; $i++)
                                                    <option value="{{ $i }}" {{ $i == 2025 ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-blue-700 hover:from-red-700 hover:to-blue-800 text-white font-medium py-3 px-4 rounded transition duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                                        <i class="fas fa-file-export mr-2"></i> Generar Reporte de Préstamos Sueltos Realizados
                                    </button>
                                </form>
                            </div>

                            <!-- NUEVO: Reporte de Categorías -->
                            <div class="bg-gradient-to-r from-red-50 to-green-50 dark:from-red-900/20 dark:to-green-900/20 rounded-lg shadow-md p-6 border border-red-200 dark:border-red-800">
                                <h2 class="text-xl font-semibold mb-4 text-center text-red-800 dark:text-red-400">
                                    <i class="fas fa-tags mr-2"></i>Reporte de Categorías
                                </h2>

                                <form action="{{ route('reports.categorias') }}" method="GET" target="_blank" class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="form-group">
                                            <label for="tipo" class="block text-gray-700 dark:text-gray-300 mb-1">Tipo:</label>
                                            <select name="tipo" id="tipo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                                <option value="">Todos los tipos</option>
                                                <option value="general">General</option>
                                                <option value="comprobante">Comprobante</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="status" class="block text-gray-700 dark:text-gray-300 mb-1">Estado:</label>
                                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                                <option value="">Todos los estados</option>
                                                <option value="activo">Activo</option>
                                                <option value="inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-green-700 hover:from-red-700 hover:to-green-800 text-white font-medium py-3 px-4 rounded transition duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                                        <i class="fas fa-file-export mr-2"></i> Generar Reporte de Categorías
                                    </button>
                                </form>
                            </div>

                            <!-- NUEVO: Reporte de Códigos QR -->
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg shadow-md p-6 border border-green-200 dark:border-green-800">
                                <h2 class="text-xl font-semibold mb-4 text-center text-green-800 dark:text-green-400">
                                    <i class="fas fa-qrcode mr-2"></i>Códigos QR para Carpetas
                                </h2>

                                <p class="text-gray-600 dark:text-gray-300 mb-4 text-center">
                                    Genera códigos QR para las carpetas que puedes imprimir, cortar y pegar en carpetas físicas.no abuse de la API
                                </p>

                                <a href="{{ route('reports.qr') }}" target="_blank" class="block w-full bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-medium py-3 px-4 rounded text-center mb-4 transition duration-300 shadow-md hover:shadow-lg">
                                    <i class="fas fa-qrcode mr-2"></i> Generar Códigos QR
                                </a>

                                <div class="mt-4">
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Filtrar QRs por categoría:</label>
                                    <form action="{{ route('reports.qr') }}" method="GET" target="_blank" class="flex items-center">
                                        <select name="categoria_id" class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md py-2 px-3">
                                            <option value="">Todas las categorías</option>
                                            @foreach(\App\Models\Category::orderBy('cat_title')->get() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->cat_title }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" onclick="navigateToCategory(event)" class="ml-2 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white py-2 px-4 rounded flex items-center justify-center shadow-md hover:shadow-lg">
                                            <i class="fas fa-filter mr-2"></i> Filtrar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.footer')
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <!-- Script para cambiar entre modo claro y oscuro -->
    <script>
        // Verificar si el usuario ya tiene una preferencia guardada
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Función para cambiar el tema
        document.getElementById('theme-toggle').addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Script para detectar modo oscuro/claro y ajustar estilos adicionales si es necesario
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica si el sistema está en modo oscuro
            const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

            // También podemos verificar si el HTML tiene la clase 'dark' para temas que usan Tailwind Dark Mode
            const htmlHasDarkClass = document.documentElement.classList.contains('dark');

            // Si se necesitan ajustes adicionales basados en el modo, se pueden hacer aquí
            if (isDarkMode || htmlHasDarkClass) {
                // Ajustes específicos para modo oscuro si son necesarios
            }
        });

        // Script para la navegación de QR por categoría
        function navigateToCategory(event) {
            event.preventDefault();
            const categoryId = document.querySelector('select[name="categoria_id"]').value;

            if (categoryId) {
                // Si hay una categoría seleccionada, usar la ruta con categoría
                window.open('{{ url("/reports/qr/categoria") }}/' + categoryId, '_blank');
            } else {
                // Si no hay categoría, usar la ruta general
                window.open('{{ route("reports.qr") }}', '_blank');
            }
        }
    </script>
</body>
</html>
