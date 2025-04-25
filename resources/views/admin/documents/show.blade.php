@extends('admin.master')
@section('content')
<div class="w-full pt-24 pb-6 px-4">
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-pink-600 dark:from-red-600 dark:to-pink-700 text-white p-4">
                <h1 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-folder-open mr-2"></i> Detalles del Préstamo
                </h1>
            </div>
             <div class="p-6">
                <!-- Información principal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Información del préstamo - Mejorado con bordes y sombras -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-md border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                            <i class="fas fa-file-alt text-blue-500 dark:text-blue-400 mr-2"></i> Información del Préstamo
                        </h2>

                        <div class="space-y-4">
                            <div class="flex flex-wrap items-center">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Solicitante:</span>
                                <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200 font-semibold">{{ $document->applicant_name ?? 'No registrado' }}</span>
                            </div>

                            <div class="flex flex-wrap items-center">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Estado:</span>
                                <span class="w-full sm:w-2/3">
                                    @if(isset($document->status))
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $document->status == 'Prestado' ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' :
                                            ($document->status == 'Devuelto' ? 'bg-gradient-to-r from-green-400 to-green-600 text-white' :
                                            'bg-gradient-to-r from-blue-400 to-blue-600 text-white') }}">
                                        {{ $document->status }}
                                    </span>
                                    @else
                                    <span class="text-gray-500 dark:text-gray-400">No definido</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Fecha de préstamo:</span>
                                <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                    @if($document->fecha_prestamo && $document->fecha_prestamo instanceof \DateTime)
                                        <span class="flex items-center">
                                            <i class="far fa-calendar-alt text-blue-500 dark:text-blue-400 mr-1"></i>
                                            {{ $document->fecha_prestamo->format('d/m/Y H:i:s') }}
                                        </span>
                                    @else
                                        No registrada
                                    @endif
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Fecha de devolución:</span>
                                <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                    @if($document->fecha_devolucion && $document->fecha_devolucion instanceof \DateTime)
                                        <span class="flex items-center">
                                            <i class="far fa-calendar-check text-blue-500 dark:text-blue-400 mr-1"></i>
                                            {{ $document->fecha_devolucion->format('d/m/Y H:i:s') }}
                                            @if($document->status == 'Prestado' && method_exists($document, 'isVencido') && $document->isVencido())
                                                <span class="ml-2 px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-semibold rounded">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Vencido
                                                </span>
                                            @endif
                                        </span>
                                    @else
                                        No registrada
                                    @endif
                                </span>
                            </div>

                            @if(isset($document->description) && $document->description)
                            <div class="flex flex-wrap items-center">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Descripción:</span>
                                <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">{{ $document->description }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información de la carpeta - Mejorado con bordes y sombras -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-md border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                            <i class="fas fa-folder text-yellow-500 dark:text-yellow-400 mr-2"></i> Información de la Carpeta
                        </h2>

                        @if(isset($document->book) && $document->book)
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Información textual -->
                            <div class="flex-1 space-y-4">
                                <div class="flex flex-wrap items-center">
                                    <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Código:</span>
                                    <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200 font-semibold">{{ $document->book->N_codigo ?? 'No definido' }}</span>
                                </div>

                                <div class="flex flex-wrap items-center">
                                    <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Título:</span>
                                    <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">{{ $document->book->title ?? 'No definido' }}</span>
                                </div>

                                <div class="flex flex-wrap items-center">
                                    <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Ubicación:</span>
                                    <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-red-500 dark:text-red-400 mr-1"></i>
                                            {{ $document->book->ubicacion ?? 'No definida' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center">
                                    <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Año:</span>
                                    <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                        <span class="flex items-center">
                                            <i class="far fa-calendar text-green-500 dark:text-green-400 mr-1"></i>
                                            {{ $document->book->year ?? 'No definido' }}
                                        </span>
                                    </span>
                                </div>

                                @if(isset($document->book->tomo) && $document->book->tomo)
                                <div class="flex flex-wrap items-center">
                                    <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Codigo Fisico:</span>
                                    <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                        <span class="flex items-center">
                                            <i class="fas fa-book text-purple-500 dark:text-purple-400 mr-1"></i>
                                            {{ $document->book->tomo }}
                                        </span>
                                    </span>
                                </div>
                                @endif
                            </div>

                            <!-- Imagen de la carpeta -->
                            @if(isset($document->book->book_img))
                            <div class="flex-shrink-0 flex items-center justify-center">
                                <div class="relative group">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg blur opacity-30 group-hover:opacity-60 transition duration-300"></div>
                                    <img src="{{ asset('book/'.$document->book->book_img) }}"
                                         alt="Imagen de la carpeta"
                                         class="relative w-32 h-40 object-cover rounded-lg transform transition duration-300 group-hover:scale-105 shadow-md"
                                         title="{{ $document->book->title }}">
                                </div>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            La carpeta asociada no existe o fue eliminada
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Información de usuario y detalles administrativos -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-md border border-gray-200 dark:border-gray-700 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-user-shield text-purple-500 dark:text-purple-400 mr-2"></i> Información Administrativa
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Información del usuario administrativo -->
                        <div>
                            <div class="flex flex-wrap items-center mb-3">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Registrado por:</span>
                                <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                    @if(isset($document->user) && $document->user)
                                        <span class="flex items-center">
                                            <i class="fas fa-user-circle text-blue-500 dark:text-blue-400 mr-1"></i>
                                            {{ $document->user->name }}
                                        </span>
                                    @else
                                        <span class="flex items-center">
                                            <i class="fas fa-user-slash text-gray-500 dark:text-gray-400 mr-1"></i>
                                            Usuario eliminado
                                        </span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center mb-3">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Fecha de registro:</span>
                                <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                    <span class="flex items-center">
                                        <i class="far fa-calendar-alt text-green-500 dark:text-green-400 mr-1"></i>
                                        {{ $document->created_at ? $document->created_at->format('d/m/Y H:i:s') : 'No registrada' }}
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Categoría e información adicional -->
                        <div>
                            @if(isset($document->category) && $document->category)
                            <div class="flex flex-wrap items-center mb-3">
                                <span class="w-full sm:w-1/3 font-medium text-gray-600 dark:text-gray-400">Categoría:</span>
                                <span class="w-full sm:w-2/3 text-gray-800 dark:text-gray-200">
                                    <span class="flex items-center">
                                        <i class="fas fa-tag text-yellow-500 dark:text-yellow-400 mr-1"></i>
                                        {{ $document->category->cat_title }}
                                    </span>
                                </span>
                            </div>
                            @endif

                            <!-- Agregar más información administrativa si es necesario -->
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de comprobantes -->
                @if(isset($estadisticas) && is_array($estadisticas))
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-chart-pie text-indigo-500 dark:text-indigo-400 mr-2"></i> Estadísticas de Comprobantes
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-r from-blue-400/80 to-indigo-600/80 dark:from-blue-500/80 dark:to-indigo-700/80 p-5 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                            <div class="flex justify-center items-center text-white mb-2">
                                <i class="fas fa-receipt text-3xl opacity-80"></i>
                            </div>
                            <div class="text-3xl font-bold text-white">{{ $estadisticas['total'] ?? 0 }}</div>
                            <div class="text-white text-opacity-90">Total</div>
                    </div>

                        <div class="bg-gradient-to-r from-yellow-400/80 to-amber-600/80 dark:from-yellow-500/80 dark:to-amber-700/80 p-5 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                            <div class="flex justify-center items-center text-white mb-2">
                                <i class="fas fa-hand-holding text-3xl opacity-80"></i>
                            </div>
                            <div class="text-3xl font-bold text-white">{{ $estadisticas['prestados'] ?? 0 }}</div>
                            <div class="text-white text-opacity-90">Prestados</div>
                        </div>

                        <div class="bg-gradient-to-r from-green-400/80 to-emerald-600/80 dark:from-green-500/80 dark:to-emerald-700/80 p-5 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                            <div class="flex justify-center items-center text-white mb-2">
                                <i class="fas fa-check-circle text-3xl opacity-80"></i>
                            </div>
                            <div class="text-3xl font-bold text-white">{{ $estadisticas['devueltos'] ?? 0 }}</div>
                            <div class="text-white text-opacity-90">Devueltos</div>
                    </div>

                        <div class="bg-gradient-to-r from-red-400/80 to-pink-500/80 dark:from-red-500/80 dark:to-pink-600/80 p-5 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                            <div class="flex justify-center items-center text-white mb-2">
                                <i class="fas fa-exclamation-triangle text-3xl opacity-80"></i>
                            </div>
                            <div class="text-3xl font-bold text-white">{{ $estadisticas['vencidos'] ?? 0 }}</div>
                            <div class="text-white text-opacity-90">Vencidos</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Lista de comprobantes prestados (Solo para carpetas de comprobantes) -->
                @if(isset($document->book) && $document->book && $document->book->isComprobanteTipo() && isset($document->comprobantes) && $document->comprobantes->count() > 0)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-receipt text-indigo-500 dark:text-indigo-400 mr-2"></i> Comprobantes Prestados
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nº Comprobante</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hojas</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha Préstamo</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha Devolución</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($document->comprobantes as $comprobante)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">#{{ $comprobante->numero_comprobante }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">{{ $comprobante->n_hojas }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block
                                            {{ $comprobante->pivot->estado == 'prestado' ?
                                                'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' :
                                                'bg-gradient-to-r from-green-400 to-green-600 text-white'
                                            }}">
                                            {{ ucfirst($comprobante->pivot->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            <div>Fecha: {{ date('d/m/Y', strtotime($comprobante->pivot->fecha_prestamo)) }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Hora: {{ date('H:i:s', strtotime($comprobante->pivot->fecha_prestamo)) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            @if($comprobante->pivot->fecha_devolucion)
                                                <div>Fecha: {{ date('d/m/Y', strtotime($comprobante->pivot->fecha_devolucion)) }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Hora: {{ date('H:i:s', strtotime($comprobante->pivot->fecha_devolucion)) }}</div>
                                            @else
                                                <span class="text-yellow-500 dark:text-yellow-400">Pendiente</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        @if($comprobante->pivot->estado === 'prestado')
                                        <button
                                            type="button"
                                            class="return-comprobante inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg shadow-sm hover:shadow-md transition duration-300"
                                            data-loan="{{ $document->id }}"
                                            data-comprobante="{{ $comprobante->id }}">
                                            <i class="fas fa-check mr-1"></i> Devolver
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Acciones - Botones mejorados -->
                <div class="flex flex-wrap gap-3 mt-8">
                    @if(isset($document->book) && $document->book && isset($document->category) && $document->category && $document->category->tipo === 'comprobante')
                    <a href="{{ route('document.comprobantes.pdf', $document->id) }}" target="_blank"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-red-400/80 to-pink-600/80 hover:from-red-500 hover:to-pink-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1 shine-button">
                        <i class="fas fa-file-pdf mr-2"></i> Ver Reporte Comprobantes PDF
                    </a>
                    @else
                    <a href="{{ route('document.general.pdf', $document->id) }}" target="_blank"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1 shine-button">
                        <i class="fas fa-file-pdf mr-2"></i> Ver Reporte General PDF
                    </a>
                    @endif

                    @if(isset($document->status) && $document->status !== 'Devuelto')
                    <button type="button"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-green-400/80 to-emerald-600/80 hover:from-green-500 hover:to-emerald-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1 shine-button mark-as-returned"
                            data-id="{{ $document->id }}">
                        <i class="fas fa-check mr-2"></i> Marcar como Devuelto
                    </button>
                    @endif

                    <a href="{{ route('document.loans.index') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-gray-400/80 to-gray-600/80 hover:from-gray-500 hover:to-gray-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1 shine-button">
                        <i class="fas fa-arrow-left mr-2"></i> Volver a la Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
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
        0% { transform: translateX(-200%) rotate(30deg); }
        100% { transform: translateX(200%) rotate(30deg); }
    }

    /* Estilos para los degradados */
    .bg-gradient-to-r {
        background-size: 200% 200%;
        animation: gradientMove 5s ease infinite;
    }

    @keyframes gradientMove {
        0% {background-position: 0% 50%;}
        50% {background-position: 100% 50%;}
        100% {background-position: 0% 50%;}
    }

    /* Mejora la visibilidad del texto en botones */
    .inline-flex {
        font-weight: 500;
        letter-spacing: 0.025em;
    }

    /* Mejora la interactividad en dispositivos móviles */
    @media (max-width: 640px) {
        .flex-wrap {
            justify-content: center;
        }

        .inline-flex {
            min-width: 200px;
            justify-content: center;
            margin-bottom: 0.5rem;
        }
    }

    /* Mejoras para las etiquetas de información */
    .w-full.sm\:w-1\/3 {
        opacity: 0.8;
        font-size: 0.9rem;
    }

    .w-full.sm\:w-2\/3 {
        font-size: 1rem;
    }

    /* Animación para íconos en hover */
    .fas, .far {
        transition: transform 0.3s ease;
    }

    *:hover > .fas, *:hover > .far {
        transform: scale(1.2);
    }

    /* Mejoras para el contraste en modo oscuro */
    .dark .border-gray-200 {
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar efectos shine en las tarjetas de estadísticas
    document.querySelectorAll('.shine-button').forEach(element => {
        element.classList.add('shine-button');
    });

    // Marcar el préstamo completo como devuelto
    $('.mark-as-returned').on('click', function() {
        var documentId = $(this).data('id');

        Swal.fire({
            title: '¿Marcar como devuelto?',
            text: "¿Está seguro de que desea marcar este préstamo como devuelto?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, marcar como devuelto',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Deshabilitar botón y mostrar carga
                $(this).prop('disabled', true)
                       .html('<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...');

                // Enviar solicitud de actualización
                $.ajax({
                    url: `/document/loan/${documentId}`,
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: "Devuelto"
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Completado!',
                                text: response.message || 'El préstamo ha sido marcado como devuelto.',
                                icon: 'success',
                                confirmButtonColor: '#4F46E5'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            // Reactivar botón en caso de error lógico
                            $('.mark-as-returned').prop('disabled', false)
                            .html('<i class="fas fa-check mr-2"></i> Marcar como Devuelto');

                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'No se pudo completar la operación.',
                                icon: 'error',
                                confirmButtonColor: '#4F46E5'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Reactivar botón
                        $('.mark-as-returned').prop('disabled', false)
                        .html('<i class="fas fa-check mr-2"></i> Marcar como Devuelto');

                        let errorMessage = 'No se pudo actualizar el estado del préstamo.';

                        // Intentar obtener el mensaje de error del servidor
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#4F46E5'
                        });
                    }
                });
            }
        });
    });

    // Devolver comprobante individual
    $(document).on('click', '.return-comprobante', function() {
        let btn = $(this);
        let loanId = btn.data('loan');
        let comprobanteId = btn.data('comprobante');

        Swal.fire({
            title: '¿Devolver comprobante?',
            text: "¿Está seguro de que desea devolver este comprobante?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true)
                   .html('<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...');

                $.ajax({
                    url: `/document/${loanId}/comprobante/${comprobanteId}/return`,
                    type: 'PUT',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            // Actualizar la fila en la tabla
                            let row = btn.closest('tr');
                            row.find('.from-yellow-400').removeClass('from-yellow-400 to-yellow-600').addClass('from-green-400 to-green-600').text('Devuelto');

                            // Eliminar botón de devolución
                            btn.remove();

                            Swal.fire({
                                icon: 'success',
                                title: '¡Devuelto!',
                                text: 'El comprobante ha sido marcado como devuelto.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            if (response.allReturned) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Completado!',
                                    text: 'Todos los comprobantes han sido devueltos. El préstamo se marcará como completado.',
                                    showConfirmButton: true
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        } else {
                            btn.prop('disabled', false)
                               .html('<i class="fas fa-check mr-1"></i> Devolver');

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'No se pudo procesar la devolución'
                            });
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false)
                           .html('<i class="fas fa-check mr-1"></i> Devolver');

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo conectar con el servidor'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection
