@extends('admin.master')
@section('content')
<div class="w-full pt-24 pb-6 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-4">
            <h1 class="text-xl font-semibold flex items-center">
                <i class="fas fa-receipt mr-2"></i> Gestión de Comprobantes
            </h1>
        </div>

        <!-- Información del préstamo -->
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <i class="fas fa-folder-open mr-2 text-blue-500"></i> Información de la Carpeta
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Título</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $document->book->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Código</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $document->book->N_codigo }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Categoría</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $document->book->category->cat_title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Año</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $document->book->year }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <i class="fas fa-clipboard-list mr-2 text-green-500"></i> Información del Préstamo
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Solicitante</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $document->applicant_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Estado</p>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block
                                    {{ $document->status == 'Prestado' ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' :
                                       'bg-gradient-to-r from-green-400 to-green-600 text-white' }}">
                                    {{ $document->status }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Préstamo</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $document->fecha_prestamo ? $document->fecha_prestamo->format('d/m/Y H:i:s') : 'No registrada' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Devolución</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $document->fecha_devolucion ? $document->fecha_devolucion->format('d/m/Y H:i:s') : 'No registrada' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-400/80 to-indigo-600/80 dark:from-blue-500/80 dark:to-indigo-700/80 p-4 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                <div class="flex justify-center items-center text-white mb-2">
                    <i class="fas fa-receipt text-3xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold text-white">{{ $estadisticas['total'] ?? 0 }}</div>
                <div class="text-white text-opacity-90">Total</div>
            </div>
            <div class="bg-gradient-to-r from-yellow-400/80 to-amber-600/80 dark:from-yellow-500/80 dark:to-amber-700/80 p-4 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                <div class="flex justify-center items-center text-white mb-2">
                    <i class="fas fa-hand-holding text-3xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold text-white">{{ $estadisticas['prestados'] ?? 0 }}</div>
                <div class="text-white text-opacity-90">Prestados</div>
            </div>
            <div class="bg-gradient-to-r from-green-400/80 to-emerald-600/80 dark:from-green-500/80 dark:to-emerald-700/80 p-4 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                <div class="flex justify-center items-center text-white mb-2">
                    <i class="fas fa-check-circle text-3xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold text-white">{{ $estadisticas['devueltos'] ?? 0 }}</div>
                <div class="text-white text-opacity-90">Devueltos</div>
            </div>
            <div class="bg-gradient-to-r from-red-400/80 to-pink-500/80 dark:from-red-500/80 dark:to-pink-600/80 p-4 rounded-lg text-center shadow-md hover:-translate-y-1 transition-transform duration-300 shine-button">
                <div class="flex justify-center items-center text-white mb-2">
                    <i class="fas fa-exclamation-triangle text-3xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold text-white">{{ $estadisticas['vencidos'] ?? 0 }}</div>
                <div class="text-white text-opacity-90">Vencidos</div>
            </div>
        </div>

        <!-- Tabla de Comprobantes -->
        <div class="p-4">
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">N° Comprobante</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hojas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha Préstamo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha Devolución</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($document->comprobantes as $comprobante)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">#{{ $comprobante->numero_comprobante }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-700 dark:text-gray-300">{{ $comprobante->n_hojas }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="{{ $comprobante->pivot->estado === 'prestado' ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }} font-semibold">
                                    {{ ucfirst($comprobante->pivot->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $comprobante->pivot->fecha_prestamo ? \Carbon\Carbon::parse($comprobante->pivot->fecha_prestamo)->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $comprobante->pivot->fecha_devolucion ? \Carbon\Carbon::parse($comprobante->pivot->fecha_devolucion)->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($comprobante->pivot->estado === 'prestado')
                                    <button
                                        type="button"
                                        class="return-comprobante inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg shadow-sm hover:shadow-md transition duration-300"
                                        data-loan="{{ $document->id }}"
                                        data-comprobante="{{ $comprobante->id }}">
                                        <i class="fas fa-check mr-1"></i> Devolver
                                    </button>
                                    @endif

                                    @if($comprobante->pdf_file)
                                    <a href="{{ asset('comprobantes/' . $comprobante->pdf_file) }}"
                                       class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg shadow-sm hover:shadow-md transition duration-300"
                                       target="_blank">
                                        <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                No hay comprobantes registrados para este préstamo
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="p-4 flex flex-wrap gap-3 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('document.loan.show', $document->id) }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-gray-400/80 to-gray-600/80 hover:from-gray-500 hover:to-gray-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1 shine-button">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Detalles
            </a>

            <a href="{{ route('document.loans.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-400/80 to-blue-600/80 hover:from-blue-500 hover:to-blue-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1 shine-button">
                <i class="fas fa-list mr-2"></i> Volver a Préstamos
            </a>

            @if($estadisticas['prestados'] > 0 && $document->status !== 'Devuelto')
            <button type="button"
                    class="mark-as-returned inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-400/80 to-emerald-600/80 hover:from-green-500 hover:to-emerald-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1 shine-button"
                    data-id="{{ $document->id }}">
                <i class="fas fa-check-circle mr-2"></i> Marcar Todo como Devuelto
            </button>
            @endif
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Aplicar efecto shine a los botones
    document.querySelectorAll('.shine-button').forEach(element => {
        element.classList.add('shine-button');
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

    // Marcar todos los comprobantes como devueltos
    $('.mark-as-returned').on('click', function() {
        var documentId = $(this).data('id');

        Swal.fire({
            title: '¿Marcar todo como devuelto?',
            text: "¿Está seguro de que desea marcar todos los comprobantes como devueltos?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, devolver todos',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Deshabilitar botón y mostrar carga
                $(this).prop('disabled', true)
                       .html('<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...');

                // Enviar solicitud de actualización
                $.ajax({
                    url: "{{ route('document.loan.update', $document->id) }}",
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: "Devuelto"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: '¡Completado!',
                            text: 'Todos los comprobantes han sido marcados como devueltos.',
                            icon: 'success',
                            confirmButtonColor: '#4F46E5'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        // Reactivar botón
                        $('.mark-as-returned').prop('disabled', false)
                        .html('<i class="fas fa-check-circle mr-2"></i> Marcar Todo como Devuelto');

                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo completar la operación.',
                            icon: 'error',
                            confirmButtonColor: '#4F46E5'
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
