@extends('admin.master')
@section('content')
<div class="w-full pt-24 pb-6 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-pink-600 dark:from-red-600 dark:to-pink-700 text-white p-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold flex items-center">
                <i class="fas fa-receipt mr-2"></i> Comprobantes
            </h1>

            <!-- Botón para volver atrás - Añadido -->
            <a href="{{ route('document.loans.index') }}"
               class="inline-flex items-center px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg transition-all duration-300 shine-button">
                <i class="fas fa-arrow-left mr-2"></i> ir a préstamos
            </a>
        </div>

        <div class="p-6">
            <!-- Información del libro/carpeta -->
            <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 p-5 rounded-lg mb-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Título de Carpeta</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->title }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Código Digial</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->N_codigo }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Codigo fisico</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->tomo }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Año</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->year }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Ubicación</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->ubicacion }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Categoría</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->category->cat_title }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Hojas</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->N_hojas }}</span>
                        </div>

                <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Descripción</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->description ?: 'Sin descripción' }}</span>
                        </div>
            </div>

            <!-- Estadísticas de comprobantes con degradados -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-r from-blue-400/80 to-indigo-600/80 dark:from-blue-500/80 dark:to-indigo-700/80 text-white rounded-lg p-5 flex items-center shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shine-button">
                    <i class="fas fa-receipt text-4xl mr-5 opacity-80"></i>
                    <div>
                        <h2 class="text-3xl font-bold">{{ $book->comprobantes->count() }}</h2>
                        <p class="opacity-80">Total Comprobantes</p>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-400/80 to-emerald-600/80 dark:from-green-500/80 dark:to-emerald-700/80 text-white rounded-lg p-5 flex items-center shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shine-button">
                    <i class="fas fa-check-circle text-4xl mr-5 opacity-80"></i>
                    <div>
                        <h2 class="text-3xl font-bold">{{ $book->comprobantes->where('estado', 'activo')->count() }}</h2>
                        <p class="opacity-80">Disponibles</p>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-400/80 to-amber-600/80 dark:from-yellow-500/80 dark:to-amber-700/80 text-white rounded-lg p-5 flex items-center shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shine-button">
                    <i class="fas fa-hand-holding text-4xl mr-5 opacity-80"></i>
                    <div>
                        <h2 class="text-3xl font-bold">{{ $book->comprobantes->where('estado', 'inactivo')->count() }}</h2>
                        <p class="opacity-80">En préstamo</p>
                        </div>
                    </div>

                <div class="bg-gradient-to-r from-cyan-400/80 to-blue-600/80 dark:from-cyan-500/80 dark:to-blue-700/80 text-white rounded-lg p-5 flex items-center shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shine-button">
                    <i class="fas fa-file text-4xl mr-5 opacity-80"></i>
                    <div>
                        <h2 class="text-3xl font-bold">{{ $book->comprobantes->sum('n_hojas') }}</h2>
                        <p class="opacity-80">Total Hojas</p>
                    </div>
                </div>
            </div>

            <!-- Botones de acción modernizados -->
            <div class="flex flex-wrap items-center gap-4 mb-6">
                <a href="{{ url('show_book') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-400/80 to-gray-600/80 hover:from-gray-500 hover:to-gray-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium shine-button">
                        <i class="fas fa-arrow-left mr-2"></i> Volver a Carpetas
                    </a>

                <a href="{{ route('books.comprobantes.create', $book->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium shine-button">
                        <i class="fas fa-plus mr-2"></i> Añadir Comprobantes
                    </a>
                    <!--aqui neseistamos el boton para sacar un reporte alos combrobantes-->
                    <a href="{{ route('books.comprobantes.report', ['book' => $book]) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium shine-button">
                        <i class="fas fa-file-pdf mr-2"></i> Generar Reporte
                    </a>
                <!-- Filtro de búsqueda mejorado -->
                <div class="ml-auto w-full sm:w-auto sm:min-w-[300px]">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300" placeholder="Buscar comprobante...">
                    </div>
                </div>
                </div>

                    @if(session()->has('message'))
                <div class="bg-green-100 dark:bg-green-900/30 border border-green-500 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center mb-4 animate-fadeIn">
                        <i class="fas fa-check-circle mr-2"></i> {{ session()->get('message') }}
                        </div>
                    @endif

            @if(session()->has('error'))
                <div class="bg-red-100 dark:bg-red-900/30 border border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center mb-4 animate-fadeIn">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session()->get('error') }}
                </div>
            @endif

            <!-- Tabla de comprobantes modernizada -->
            <div class="overflow-x-auto bg-white/5 dark:bg-gray-900/10 rounded-lg backdrop-blur-sm shadow-md">
                <table class="min-w-full" id="comprobantes-table">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">N° Comprobante</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Código</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">N° Hojas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Costo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Descripción</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">PDF</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Última Actualización</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($book->comprobantes as $comprobante)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700/40">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $comprobante->numero_comprobante }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $comprobante->codigo_personalizado ?: '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $comprobante->n_hojas }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    @if($comprobante->costo)
                                        <span class="font-medium">Bs {{ number_format($comprobante->costo, 2) }}</span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    @if(strlen($comprobante->descripcion) > 30)
                                        <div class="relative group">
                                            <span class="tooltip-trigger cursor-help">{{ substr($comprobante->descripcion, 0, 30) }}...</span>
                                            <div class="absolute z-10 w-64 p-2 bg-white dark:bg-gray-800 rounded shadow-lg border border-gray-200 dark:border-gray-700 text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none -mt-1 left-0">
                                                {{ $comprobante->descripcion ?: 'Sin descripción' }}
                                            </div>
                                        </div>
                                    @else
                                        {{ $comprobante->descripcion ?: 'Sin descripción' }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                        @if($comprobante->pdf_file)
                                        <a href="{{ asset('comprobantes/'.$comprobante->pdf_file) }}"
                                           target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline inline-flex items-center">
                                            <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                                        </a>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-500">Sin PDF</span>
                                        @endif
                                    </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block
                                        {{ $comprobante->estado == 'activo' ? 'bg-gradient-to-r from-green-400 to-green-600 text-white' : 'bg-gradient-to-r from-red-400 to-red-600 text-white' }}">
                                        {{ ucfirst($comprobante->estado) }}
                                            </span>
                                    </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $comprobante->updated_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg text-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-sm shine-button"
                                                data-id="{{ $comprobante->id }}"
                                                onclick="viewComprobanteDetails({{ $comprobante->id }})">
                                            <i class="fas fa-eye mr-1"></i> Ver detalles
                                        </button>
                                        <a href="{{ route('books.comprobantes.edit', [$book->id, $comprobante->id]) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-yellow-400/80 to-amber-600/80 hover:from-yellow-500 hover:to-amber-700 text-white rounded-lg text-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-sm shine-button">
                                            <i class="fas fa-edit mr-1"></i> Editar
                                        </a>
                                        <form action="{{ route('books.comprobantes.destroy', [$book->id, $comprobante->id]) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-400/80 to-pink-600/80 hover:from-red-500 hover:to-pink-700 text-white rounded-lg text-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-sm shine-button" onclick="return confirm('¿Estás seguro de eliminar este comprobante?')">
                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 italic">
                                    <i class="fas fa-info-circle mr-2"></i> No hay comprobantes registrados para esta carpeta
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles del comprobante (mejorado para modo oscuro/claro) -->
<div id="viewComprobanteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-auto shadow-xl transform transition-all">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h5 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center gap-2"><i class="fas fa-receipt"></i> Detalles del Comprobante</h5>
                <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="document.getElementById('viewComprobanteModal').classList.add('hidden')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="comprobante-details-container">
                    <div class="text-center py-8">
                        <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-blue-500 border-r-transparent">
                            <span class="sr-only">Cargando...</span>
                            </div>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Cargando detalles...</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-400/80 to-gray-600/80 hover:from-gray-500 hover:to-gray-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-sm shine-button" onclick="document.getElementById('viewComprobanteModal').classList.add('hidden')">
                    <i class="fas fa-times mr-2"></i> Cerrar
                </button>
    </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    /* Efecto de brillo para los botones */
    .shine-button {
        position: relative;
        overflow: hidden;
    }

    .shine-button:after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
        transform: rotate(30deg);
        opacity: 0;
        transition: opacity 0.6s;
    }

    .shine-button:hover:after {
        opacity: 1;
        animation: shine 1.5s;
    }

    @keyframes shine {
        0% {
            transform: translateX(-200%) rotate(30deg);
        }
        100% {
            transform: translateX(200%) rotate(30deg);
        }
    }

    /* Animación para los degradados */
    .bg-gradient-to-r {
        background-size: 200% 200%;
        animation: gradientMove 5s ease infinite;
    }

    @keyframes gradientMove {
        0% {background-position: 0% 50%;}
        50% {background-position: 100% 50%;}
        100% {background-position: 0% 50%;}
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in-out;
    }

    /* Estilos mejorados para la paginación */
    .dataTables_paginate {
        text-align: right;
        padding-top: 10px !important;
    }

    .dataTables_paginate .paginate_button {
        display: inline-block !important;
        padding: 5px 10px !important;
        margin-left: 2px !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        background: #f3f4f6 !important;
        color: #374151 !important;
        border: 1px solid #d1d5db !important;
        text-decoration: none !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #e5e7eb !important;
        color: #111827 !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        color: white !important;
        border-color: #2563eb !important;
    }

    .dataTables_paginate .paginate_button.disabled {
        color: #9ca3af !important;
        background: #f3f4f6 !important;
        cursor: not-allowed !important;
    }

    /* Modo oscuro para paginación */
    .dark .dataTables_paginate .paginate_button {
        background: #374151 !important;
        color: #d1d5db !important;
        border-color: #4b5563 !important;
    }

    .dark .dataTables_paginate .paginate_button:hover {
        background: #4b5563 !important;
        color: #f9fafb !important;
    }

    .dark .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        color: white !important;
        border-color: #2563eb !important;
    }

    .dark .dataTables_paginate .paginate_button.disabled {
        color: #6b7280 !important;
        background: #374151 !important;
    }
</style>
@endpush

@push('scripts')
<script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Añadir clase shine-button a todas las tarjetas de estadísticas
    $('.shine-button').each(function() {
        $(this).addClass('shine-button');
    });

    // Inicializar DataTable con búsqueda avanzada
    const table = $('#comprobantes-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true,
        ordering: true,
        "dom": '<"flex flex-col sm:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center mt-4"ip>',
        "pageLength": 25,
        "pagingType": "full_numbers",
        "drawCallback": function(settings) {
            // Asegurarse que la paginación esté en una sola línea horizontal
            $('.dataTables_paginate').addClass('flex flex-row flex-wrap justify-end gap-1');
        }
    });

    // Buscar en múltiples columnas
    $('#searchInput').on('keyup', function() {
        const searchTerm = $(this).val();

        // Realizar búsqueda global que afecta a todas las columnas
        table.search(searchTerm).draw();
    });
});

// Función para ver detalles del comprobante (actualizada para modo oscuro/claro)
function viewComprobanteDetails(comprobanteId) {
    // Mostrar el modal
    document.getElementById('viewComprobanteModal').classList.remove('hidden');

    // Obtener los detalles del comprobante usando la ruta correcta
    fetch(`/document/comprobante/${comprobanteId}/details`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const comprobante = data.comprobante;
            const prestamo = data.prestamo;

            let detallesHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-base font-semibold text-gray-800 dark:text-gray-200 flex items-center mb-3">
                            <i class="fas fa-receipt mr-2"></i> Información del Comprobante
                        </h5>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Número:</th>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">${comprobante.numero_comprobante}</td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Código:</th>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">${comprobante.codigo_personalizado || '-'}</td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Hojas:</th>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">${comprobante.n_hojas}</td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Costo:</th>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">${comprobante.costo ? 'Bs ' + parseFloat(comprobante.costo).toFixed(2) : '-'}</td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Estado:</th>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block
                                                ${comprobante.estado == 'activo' ? 'bg-gradient-to-r from-green-400 to-green-600 text-white' : 'bg-gradient-to-r from-red-400 to-red-600 text-white'}">
                                                ${comprobante.estado.charAt(0).toUpperCase() + comprobante.estado.slice(1)}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Fecha creación:</th>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">${new Date(comprobante.created_at).toLocaleDateString()} ${new Date(comprobante.created_at).toLocaleTimeString()}</td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Última actualización:</th>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">${new Date(comprobante.updated_at).toLocaleDateString()} ${new Date(comprobante.updated_at).toLocaleTimeString()}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    ${prestamo ? `
                        <div>
                            <h5 class="text-base font-semibold text-gray-800 dark:text-gray-200 flex items-center mb-3">
                                <i class="fas fa-calendar-alt mr-2"></i> Información del Préstamo
                            </h5>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        <tr>
                                            <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Fecha Préstamo:</th>
                                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">${new Date(prestamo.fecha_prestamo).toLocaleDateString()}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 text-left bg-gray-100 dark:bg-gray-800">Estado:</th>
                                            <td class="px-4 py-2 text-sm">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block bg-gradient-to-r from-yellow-400 to-yellow-600 text-white">
                                                    ${prestamo.estado.charAt(0).toUpperCase() + prestamo.estado.slice(1)}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ` : ''}
                </div>
                ${comprobante.descripcion ? `
                    <div class="mt-6">
                        <h5 class="text-base font-semibold text-gray-800 dark:text-gray-200 flex items-center mb-3">
                            <i class="fas fa-align-left mr-2"></i> Descripción
                        </h5>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                            <p class="text-gray-700 dark:text-gray-300">${comprobante.descripcion}</p>
                        </div>
                    </div>
                ` : ''}
                ${comprobante.pdf_file ? `
                    <div class="mt-6 text-center">
                        <a href="/comprobantes/${comprobante.pdf_file}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-400/80 to-red-600/80 hover:from-red-500 hover:to-red-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 shadow-md hover:shadow-lg shine-button"
                           target="_blank">
                            <i class="fas fa-file-pdf mr-2"></i> Ver PDF del Comprobante
                        </a>
                    </div>
                ` : ''}
            `;

            document.getElementById('comprobante-details-container').innerHTML = detallesHTML;
        } else {
            document.getElementById('comprobante-details-container').innerHTML = `
                <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 p-4 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> Error al cargar los detalles: ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error al obtener detalles del comprobante:', error);
        document.getElementById('comprobante-details-container').innerHTML = `
            <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 p-4 rounded-lg flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> Error al conectar con el servidor
            </div>
        `;
    });
}
</script>
@endpush
@endsection
