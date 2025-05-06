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
    @include('admin.css')
    <!-- Agregar estilos específicos para esta página -->
    <style>
        .page-content {
            padding-top: 8rem;
            margin-top: 2rem;
        }

        .header-hidden .page-content {
            padding-top: 1.25rem;
            margin-top: 0;
        }

        .btn-crear {
            position: fixed;
            right: 1.5rem;
            z-index: 40;
            top: 90px;
            transition: top 0.3s ease;
        }

        .header-hidden .btn-crear {
            top: 20px;
        }

        /* Estilos para el loader */
        #loader {
            transition: opacity 0.5s ease;
        }

        #loader.hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen" id="app-body">
    <!-- Loader -->
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 backdrop-blur-sm">
        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    @include('admin.header')
    @include('admin.sidebar')

    <main id="pageContent" class="page-content min-h-screen w-full p-5 transition-all duration-300">
        <div class="container mx-auto">
            <!-- Botón Crear Nuevo (en posición original) -->
            <a href="{{ url('add_book') }}" id="btnCrear" class="btn-crear inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 via-white to-red-500 hover:from-red-600 hover:to-red-600 text-red-600 font-medium rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 duration-300">
                <i class="fas fa-plus-circle mr-2"></i> Añadir Nuevo
            </a>

            <!-- Buscador compacto -->
            <div class="mb-6 flex justify-end">
                <form action="{{ route('show_book') }}" method="GET" class="flex items-center">
                    <div class="relative">
                        <input type="text" id="search_codigo" name="search_codigo"
                            value="{{ request('search_codigo') }}"
                            class="pl-10 pr-4 py-2 w-64 rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Buscar por código digital">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <button type="submit" class="ml-2 px-4 py-2 bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:via-gray-100 hover:to-blue-600 text-gray-900 font-medium rounded-full transition duration-200">
                        Buscar
                    </button>
                    @if(request('search_codigo'))
                    <a href="{{ route('show_book') }}" class="ml-2 px-3 py-2 bg-gradient-to-r from-red-500 via-white to-red-500 hover:from-red-600 hover:via-gray-100 hover:to-red-600 text-gray-900 font-medium rounded-full transition duration-200">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </form>
            </div>

            <!-- Alerta de mensaje -->
            @if(session()->has('message'))
                <div class="relative mb-6 p-4 border border-green-500 bg-green-50 dark:bg-green-900/10 text-green-600 dark:text-green-400 rounded-lg animate-fadeIn">
                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-green-500 hover:text-green-700 dark:hover:text-green-300 transition-colors" data-dismiss="alert">×</button>
                    {{session()->get('message')}}
                </div>
            @endif

            <!-- Navegación de categorías -->
            <div class="flex gap-4 p-4 mb-6 overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <a href="{{ route('show_book') }}" class="px-4 py-2 rounded-full text-sm {{ !request('category') ? 'bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600' : 'text-blue-600 hover:bg-gradient-to-r hover:from-blue-500 hover:via-white hover:to-blue-500' }} transition-colors whitespace-nowrap">
                    Todas Carpetas
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('show_book.category', $category->id) }}"
                       class="px-4 py-2 rounded-full text-sm {{ request('category') == $category->id ? 'bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600' : 'text-blue-600 hover:bg-gradient-to-r hover:from-blue-500 hover:via-white hover:to-blue-500' }} transition-colors whitespace-nowrap">
                        {{ $category->cat_title }}
                    </a>
                @endforeach
            </div>

            <!-- Controles de ordenamiento -->
            <div class="flex items-center gap-4 p-4 mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <span class="text-gray-700 dark:text-gray-300">Ordenar por:</span>
                <div class="flex gap-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'N_codigo', 'sort_order' => ($sortBy === 'N_codigo' && $sortOrder === 'asc') ? 'desc' : 'asc']) }}"
                       class="inline-flex items-center px-4 py-2 rounded-full text-sm {{ $sortBy === 'N_codigo' ? 'bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600' : 'text-blue-600 hover:bg-gradient-to-r hover:from-blue-500 hover:via-white hover:to-blue-500' }} transition-colors">
                        Código
                        @if($sortBy === 'N_codigo')
                            <i class="fas fa-sort-{{ $sortOrder === 'asc' ? 'up' : 'down' }} ml-2"></i>
                        @endif
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'fecha_modificacion', 'sort_order' => ($sortBy === 'fecha_modificacion' && $sortOrder === 'asc') ? 'desc' : 'asc']) }}"
                       class="inline-flex items-center px-4 py-2 rounded-full text-sm {{ $sortBy === 'fecha_modificacion' ? 'bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600' : 'text-blue-600 hover:bg-gradient-to-r hover:from-blue-500 hover:via-white hover:to-blue-500' }} transition-colors">
                        Fecha de Modificación
                        @if($sortBy === 'fecha_modificacion')
                            <i class="fas fa-sort-{{ $sortOrder === 'asc' ? 'up' : 'down' }} ml-2"></i>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Tabla de libros -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg backdrop-blur-lg p-5 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">N_CODIGO</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">TITULO</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">UBICACION</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">AÑO</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">C.DIGITAL</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">DESCRIPCION</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">CATEGORIA</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">N_HOJAS</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">PDF</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">IMAGEN</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">ACCIONES</th>
                            <th class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-blue-500 dark:border-blue-400">QR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($books as $book)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">{{$book->N_codigo}}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">{{$book->title}}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">{{$book->ubicacion}}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">{{$book->year}}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">{{$book->tomo}}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                    @if(strlen($book->description) > 30)
                                        <div class="relative group">
                                            <span class="tooltip-trigger cursor-help">{{ substr($book->description, 0, 30) }}...</span>
                                            <div class="absolute z-10 w-64 p-2 bg-white dark:bg-gray-800 rounded shadow-lg border border-gray-200 dark:border-gray-700 text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none -mt-1 left-0">
                                                {{ $book->description }}
                                            </div>
                                        </div>
                                    @else
                                        {{ $book->description }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">{{$book->category->cat_title}}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">{{$book->N_hojas}}</td>
                                <td class="px-4 py-3 text-sm border-b border-gray-200 dark:border-gray-700">
                                    @if ($book->pdf_file)
                                        <a href="{{ asset('pdfs/' . $book->pdf_file) }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition-colors" target="_blank">
                                            <i class="fas fa-file-pdf"></i> Ver PDF
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">No disponible</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-200 dark:border-gray-700">
                                    <img class="w-20 h-20 object-cover rounded-lg transition transform hover:scale-110 hover:shadow-lg cursor-pointer"
                                         src="{{ asset('book/'.$book->book_img) }}"
                                         alt="Portada"
                                         ondblclick="openImageModal(this.src, '{{$book->title}}')">
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex flex-col md:flex-row gap-2 justify-center">
                                        @if($book->category && ($book->category->tipo == 'comprobante' || strpos(strtolower($book->category->cat_title), 'comprobante') !== false))
                                            <a href="{{ route('books.comprobantes.index', $book->id) }}"
                                               class="px-4 py-2 bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600 rounded-md inline-flex items-center transition duration-300 transform hover:scale-105">
                                                <i class="fas fa-file-invoice mr-2"></i>
                                                G.C.
                                            </a>
                                        @endif
                                        <a onclick="confirmation(event)" href="{{url('book_delete',$book->id)}}"
                                           class="inline-flex items-center justify-center px-3 py-1.5 bg-gradient-to-r from-red-500 via-white to-red-500 hover:from-red-600 hover:to-red-600 text-red-600 text-xs font-medium rounded-full transition duration-300 transform hover:scale-110 hover:shadow-md">
                                            <i class="fas fa-trash-alt mr-1"></i>
                                        </a>
                                        <a href="{{url('edit_book',$book->id)}}"
                                           class="inline-flex items-center justify-center px-3 py-1.5 bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600 text-xs font-medium rounded-full transition duration-300 transform hover:scale-110 hover:shadow-md">
                                            <i class="fas fa-edit mr-1"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-200 dark:border-gray-700">
                                    <button onclick="showQrCode({{$book->id}}, '{{$book->N_codigo}}')" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 via-white to-green-500 hover:from-green-600 hover:to-green-600 text-green-600 text-xs font-medium rounded-full transition duration-300 transform hover:scale-110 hover:shadow-md">
                                        <i class="fas fa-qrcode mr-2"></i> Generar QR
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="mt-6">
                    <div class="pagination flex justify-center space-x-1">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.footer')

    <!-- Modal para visualización de imágenes -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-75" onclick="closeImageModal()"></div>
        <div class="relative h-full w-full flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                    <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <div class="p-4 flex justify-center">
                    <img id="modalImage" class="max-h-[70vh] object-contain" src="" alt="Imagen ampliada">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualización de QR -->
    <div id="qrModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-75" onclick="closeQrModal()"></div>
        <div class="relative h-full w-full flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 id="qrModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Código QR</h3>
                    <button onclick="closeQrModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <div class="p-6 flex flex-col items-center">
                    <div id="qrLoadingSpinner" class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                    <div id="qrImageContainer" class="hidden mb-4">
                        <img id="qrImage" class="w-64 h-64" src="" alt="Código QR">
                    </div>
                    <p class="text-center text-gray-700 dark:text-gray-300 mb-4">
                        Escanee este código QR para ver los detalles de la carpeta.<br>
                        <span id="qrBookCode" class="font-semibold"></span>
                    </p>
                    <div class="flex gap-3 mt-2">
                        <a id="qrDownloadLink" download="QR_Codigo.png" href="#" class="px-4 py-2 bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600 rounded-full inline-flex items-center transition duration-300">
                            <i class="fas fa-download mr-2"></i> Descargar QR
                        </a>
                        <a id="qrTestLink" target="_blank" href="#" class="px-4 py-2 bg-gradient-to-r from-green-500 via-white to-green-500 hover:from-green-600 hover:to-green-600 text-green-600 rounded-full inline-flex items-center transition duration-300">
                            <i class="fas fa-external-link-alt mr-2"></i> Probar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        // Ocultar el loader inmediatamente
        document.addEventListener('DOMContentLoaded', function() {
            // Ocultar el loader
            const loader = document.getElementById('loader');
            if (loader) {
                loader.classList.add('hidden');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }

            // Detectar si el header está oculto y aplicar la clase correspondiente
            const header = document.querySelector('.header-area');
            const body = document.getElementById('app-body');

            if (header && body) {
                if (localStorage.getItem('headerHidden') === 'true') {
                    body.classList.add('header-hidden');
                }

                // Observer para cambios en el header
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') {
                            if (header.classList.contains('hidden')) {
                                body.classList.add('header-hidden');
                            } else {
                                body.classList.remove('header-hidden');
                            }
                        }
                    });
                });

                observer.observe(header, { attributes: true });
            }
        });

        // Función de confirmación para eliminar
        function confirmation(event) {
            event.preventDefault();
            if (confirm('¿Está seguro que desea eliminar esta carpeta?')) {
                window.location.href = event.target.closest('a').href;
            }
        }

        // Funciones para el modal de imágenes
        function openImageModal(imageSrc, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');

            modalImage.src = imageSrc;
            modalTitle.textContent = title;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Cerrar modal con la tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

        // Funciones para el modal de QR
        function showQrCode(bookId, bookCode) {
            const modal = document.getElementById('qrModal');
            const loadingSpinner = document.getElementById('qrLoadingSpinner');
            const qrImageContainer = document.getElementById('qrImageContainer');
            const qrImage = document.getElementById('qrImage');
            const qrBookCode = document.getElementById('qrBookCode');
            const qrDownloadLink = document.getElementById('qrDownloadLink');
            const qrTestLink = document.getElementById('qrTestLink');

            // Mostrar modal y spinner
            modal.classList.remove('hidden');
            loadingSpinner.classList.remove('hidden');
            qrImageContainer.classList.add('hidden');
            document.body.style.overflow = 'hidden';

            // Establecer el código del libro
            qrBookCode.textContent = 'Código: ' + bookCode;

            // Hacer la petición para generar el QR
            fetch('{{ url("/qr-generate") }}/' + bookId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Establecer la imagen del QR
                        qrImage.src = data.qr_image_url;
                        qrDownloadLink.href = data.qr_image_url;
                        qrTestLink.href = '{{ url("/qr-info") }}/' + bookId;

                        // Ocultar spinner y mostrar imagen
                        loadingSpinner.classList.add('hidden');
                        qrImageContainer.classList.remove('hidden');
                    } else {
                        alert('Error al generar el código QR: ' + data.message);
                        closeQrModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al generar el código QR. Por favor intente nuevamente.');
                    closeQrModal();
                });
        }

        function closeQrModal() {
            const modal = document.getElementById('qrModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Cerrar modal QR con la tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeQrModal();
                closeImageModal();
            }
        });
    </script>
</body>
</html>
