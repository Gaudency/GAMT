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
    <title>Detalles de la Carpeta</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos adicionales aquí */
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">
    @include('admin.header')

    <!-- Añadido padding-top para respetar el header -->
    <div class="pt-16 md:pt-20">
        <div class="flex flex-col md:flex-row">
            @include('admin.sidebar')
            <div class="flex-1 p-4 md:p-6">
                <div class="max-w-6xl mx-auto">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-red-500 via-white to-violet-500 dark:from-red-600 dark:via-gray-200 dark:to-violet-600 px-6 py-4">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-900">{{ $book->title }}</h2>
                        </div>

                        <div class="p-6">
                            <div class="flex flex-col md:flex-row gap-8">
                                <!-- Imagen del libro -->
                                <div class="md:w-1/3">
                                    <img src="{{ asset('book/' . $book->book_img) }}"
                                         class="w-full h-auto rounded-lg shadow-md border-2 border-emerald-500 dark:border-emerald-600"
                                         alt="{{ $book->title }}">
                                </div>

                                <!-- Detalles del libro -->
                                <div class="md:w-2/3">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 border-l-4 border-green-500">
                                            <p class="font-medium text-green-700 dark:text-green-300">Código</p>
                                            <p class="text-lg">{{ $book->N_codigo }}</p>
                                        </div>

                                        <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4 border-l-4 border-red-500">
                                            <p class="font-medium text-red-700 dark:text-red-300">Año</p>
                                            <p class="text-lg">{{ $book->year }}</p>
                                        </div>

                                        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 border-l-4 border-green-500">
                                            <p class="font-medium text-green-700 dark:text-green-300">Ubicación</p>
                                            <p class="text-lg">{{ $book->ubicacion }}</p>
                                        </div>

                                        <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4 border-l-4 border-red-500">
                                            <p class="font-medium text-red-700 dark:text-red-300">Codigo Fisico</p>
                                            <p class="text-lg">{{ $book->tomo }}</p>
                                        </div>

                                        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 border-l-4 border-green-500 sm:col-span-2">
                                            <p class="font-medium text-green-700 dark:text-green-300">Categoría</p>
                                            <p class="text-lg">{{ $book->category->cat_title }}</p>
                                        </div>

                                        <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4 border-l-4 border-red-500 sm:col-span-2">
                                            <p class="font-medium text-red-700 dark:text-red-300">Descripción</p>
                                            <p class="text-lg">{{ $book->description }}</p>
                                        </div>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="flex flex-wrap gap-3 mt-6">
                                        @if($book->pdf_file)
                                            <a href="{{ asset('pdfs/'.$book->pdf_file) }}"
                                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors shadow-md"
                                               target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                Ver PDF
                                            </a>
                                        @endif

                                        @if($book->estado !== 'Prestado')
                                            @if($book->isComprobanteTipo())
                                                <button type="button"
                                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-400 hover:from-red-600 hover:to-rose-500 text-white rounded-lg transition-colors shadow-md"
                                                        onclick="document.getElementById('prestamoModal').classList.remove('hidden')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                    Prestar Comprobantes
                                                </button>
                                            @else
                                                <a href="{{ route('document.loan.create', $book->id) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-400 hover:from-red-600 hover:to-rose-500 text-white rounded-lg transition-colors shadow-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                    Prestar Carpeta
                                                </a>
                                            @endif
                                        @else
                                            <button class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg opacity-75 cursor-not-allowed" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Carpeta en Préstamo
                                            </button>
                                        @endif

                                        {{-- Botón Gestionar Comprobantes --}}
                                        @if($book->isComprobanteTipo())
                                            <a href="{{ route('books.comprobantes.index', $book->id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-400 hover:from-red-600 hover:to-rose-500 text-white rounded-lg shadow hover:shadow-lg transition duration-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                </svg>
                                                Gestionar Comprobantes
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para préstamo de comprobantes -->
                @if($book->isComprobanteTipo())
                    <div id="prestamoModal" class="fixed inset-0 hidden z-[100] overflow-y-auto bg-black bg-opacity-50">
                        <div class="min-h-screen px-4 text-center" style="padding-top: 5rem;">
                            <div class="inline-block align-middle bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-3xl w-full max-h-[80vh] overflow-y-auto relative">
                                <div class="bg-gradient-to-r from-red-500 to-rose-400 p-4 rounded-t-xl sticky top-0 z-[101]">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-xl font-bold text-white">Préstamo de Comprobantes</h3>
                                        <button type="button"
                                                class="text-white hover:text-gray-200 focus:outline-none"
                                                onclick="document.getElementById('prestamoModal').classList.add('hidden')">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <form id="loanForm" method="POST" action="{{ route('document.loan.store') }}" onsubmit="submitForm(event)">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Nombre del Solicitante
                                            </label>
                                            <input type="text"
                                                   name="applicant_name"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                                                   required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Tipo de Préstamo
                                            </label>
                                            <select name="tipo_prestamo"
                                                    id="tipoPrestamo"
                                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                                                    required>
                                                <option value="completo">Carpeta Completa</option>
                                                <option value="parcial">Comprobantes Individuales</option>
                                            </select>
                                        </div>

                                        <div id="comprobantesSelector" class="hidden mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Seleccionar Comprobantes
                                            </label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                                @foreach($book->comprobantes as $comprobante)
                                                    <div class="flex items-center space-x-2">
                                                        <input type="checkbox"
                                                               name="comprobantes[]"
                                                               value="{{ $comprobante->id }}"
                                                               id="comp_{{ $comprobante->id }}"
                                                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                                        <label for="comp_{{ $comprobante->id }}" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Comprobante #{{ $comprobante->numero_comprobante }}
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $comprobante->n_hojas }} hojas)</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Fecha de Devolución
                                            </label>
                                            <input type="date"
                                                   name="fecha_devolucion"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                                                   required>
                                        </div>

                                        <div class="flex justify-center">
                                            <button type="submit"
                                                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-rose-400 hover:from-red-600 hover:to-rose-500 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition duration-300">
                                                Registrar Préstamo
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.footer')

    <script>
    function submitForm(e) {
        e.preventDefault();

        const form = document.getElementById('loanForm');
        const formData = new FormData(form);

        // Mostrar indicador de carga
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loadingIndicator';
        loadingIndicator.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingIndicator.innerHTML = `
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
                <p class="text-gray-700 dark:text-gray-300">Procesando préstamo...</p>
            </div>
        `;
        document.body.appendChild(loadingIndicator);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Eliminar indicador de carga
            document.getElementById('loadingIndicator').remove();

            if (data.success) {
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message || 'Préstamo registrado correctamente',
                    confirmButtonColor: '#3B82F6'
                }).then(() => {
                    // Redirigir a la página de préstamos
                    window.location.href = data.redirect;
                });
            } else {
                // Mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Ocurrió un error al procesar el préstamo',
                    confirmButtonColor: '#3B82F6'
                });
            }
        })
        .catch(error => {
            // Eliminar indicador de carga
            if (document.getElementById('loadingIndicator')) {
                document.getElementById('loadingIndicator').remove();
            }

            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar el préstamo. Por favor, inténtelo de nuevo.',
                confirmButtonColor: '#3B82F6'
            });
        });
    }

    document.getElementById('tipoPrestamo')?.addEventListener('change', function() {
        const comprobantesSelector = document.getElementById('comprobantesSelector');
        if (this.value === 'parcial') {
            comprobantesSelector.classList.remove('hidden');
        } else {
            comprobantesSelector.classList.add('hidden');
        }
    });
    </script>
</body>
</html>

