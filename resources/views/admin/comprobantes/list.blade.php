@extends('admin.master')
@section('content')
<div class="w-full pt-24 pb-6 px-4">
    <div class="flex justify-center">
        <div class="w-full lg:w-5/6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 dark:from-red-600 dark:to-pink-700 text-white p-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-receipt mr-2"></i> Comprobantes de la Carpeta
                    </h2>

                    <!-- Botón para volver atrás - Añadido -->
                    <a href="{{ url('show_book') }}"
                       class="inline-flex items-center px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg transition-all duration-300 shine-button">
                        <i class="fas fa-arrow-left mr-2"></i> Volver a carpetas
                    </a>
                </div>

                <div class="p-6">
                    <!-- Información de la carpeta -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 p-5 rounded-lg mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Título de Carpeta</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->title }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Código</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->N_codigo }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tomo</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->tomo }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Año</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->year }}</span>
                        </div>
                    </div>

                    <!-- Estadísticas con degradados modernos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-blue-400/80 to-indigo-600/80 dark:from-blue-500/80 dark:to-indigo-700/80 text-white rounded-lg p-5 flex items-center shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shine-button">
                            <i class="fas fa-receipt text-4xl mr-5 opacity-80"></i>
                            <div>
                                <h2 class="text-3xl font-bold">{{ $book->comprobantes->count() }}</h2>
                                <p class="opacity-80">Total</p>
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
                            <i class="fas fa-arrow-left"></i> Volver a Carpetas
                        </a>
                        <a href="{{ route('comprobantes.create', $book->id) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium shine-button">
                            <i class="fas fa-plus mr-2"></i> Crear Comprobantes
                        </a>

                        <!-- Filtro de búsqueda adaptado para modo claro/oscuro -->
                        <div class="ml-auto w-full sm:w-auto sm:min-w-[300px]">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300" placeholder="Buscar comprobante...">
                            </div>
                        </div>
                    </div>

                    <!-- Alertas -->
                    <div id="alert-container"></div>

                    <!-- Tabla de comprobantes -->
                    <div class="bg-white/5 dark:bg-gray-900/10 rounded-lg p-4 mb-6 shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-gray-800 dark:text-gray-200" id="comprobantes-table">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">N° Comprobante</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">N° Hojas</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">PDF</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($book->comprobantes as $comprobante)
                                    <tr data-id="{{ $comprobante->id }}" class="hover:bg-gray-100 dark:hover:bg-gray-700/40">
                                        <td class="px-4 py-3">{{ $comprobante->numero_comprobante }}</td>
                                        <td class="px-4 py-3">
                                            <div>
                        <input type="number"
                                                       class="hojas-input w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                               value="{{ $comprobante->n_hojas }}"
                                                       min="0">
                                            </div>
                    </td>
                                        <td class="px-4 py-3">
                                            <div class="pdf-container">
                        @if($comprobante->pdf_file)
                            <a href="{{ asset('comprobantes/'.$comprobante->pdf_file) }}"
                                                   target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline inline-flex items-center">
                                                    <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                                                </a>
                        @endif
                                                <div class="mt-2">
                                                    <div class="relative">
                        <input type="file"
                                                               class="pdf-input w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                                               accept=".pdf">
                                                        <small class="block mt-1 text-gray-500 dark:text-gray-400 pdf-name text-xs"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block
                                                {{ $comprobante->estado == 'activo' ? 'bg-gradient-to-r from-green-400 to-green-600 text-white' : 'bg-gradient-to-r from-red-400 to-red-600 text-white' }}">
                                                {{ ucfirst($comprobante->estado) }}
                                            </span>
                    </td>
                                        <td class="px-4 py-3">
                                            <button class="save-comprobante inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium shine-button">
                                                <i class="fas fa-save mr-2"></i> Guardar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
                </div>
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
        0% { transform: translateX(-200%) rotate(30deg); }
        100% { transform: translateX(200%) rotate(30deg); }
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

    /* Estilo para botón en estado de carga */
    .loading-btn {
        @apply opacity-70 cursor-not-allowed;
    }
</style>
@endpush

@push('scripts')
<script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar el efecto shine en botones
    document.querySelectorAll('.shine-button').forEach(element => {
        element.classList.add('shine-button');
    });

    // Inicializar DataTable
    var dataTable = $('#comprobantes-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true,
        "dom": 'lrtp', // Ocultar el buscador nativo
        "pageLength": 25
    });

    // Búsqueda en la tabla
    $('#searchInput').on('keyup', function() {
        dataTable.search($(this).val()).draw();
    });

    // Mostrar nombre del archivo seleccionado
    $('.pdf-input').on('change', function() {
        const fileName = this.files[0]?.name || '';
        $(this).closest('.relative').find('.pdf-name').text(fileName ? `Archivo: ${fileName}` : '');
    });

    // Guardar cambios en el comprobante
    $('.save-comprobante').on('click', function() {
        const btn = $(this);
        const row = btn.closest('tr');
        const id = row.data('id');
        const hojasInput = row.find('.hojas-input');
        const pdfInput = row.find('.pdf-input');

        // Validación básica
        if (hojasInput.val() === '' || parseInt(hojasInput.val()) < 0) {
            showAlert('El número de hojas debe ser un valor positivo', 'danger');
            return;
        }

        // Mostrar estado de carga
        btn.addClass('loading-btn opacity-70 cursor-not-allowed').html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        // Preparar datos
        const formData = new FormData();
        formData.append('n_hojas', hojasInput.val());
        formData.append('_method', 'PUT'); // Para simular PUT en Laravel

        if (pdfInput[0].files.length > 0) {
            formData.append('pdf_file', pdfInput[0].files[0]);
        }

        formData.append('_token', '{{ csrf_token() }}');

        // Enviar solicitud
        fetch(`/comprobantes/${id}`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('Comprobante actualizado correctamente', 'success');

                // Actualizar el enlace de PDF si hay uno nuevo
                if (pdfInput[0].files.length > 0 && data.pdf_file) {
                    const pdfContainer = row.find('.pdf-container');
                    pdfContainer.find('.text-blue-600, .text-blue-400').remove();
                    pdfContainer.prepend(`
                        <a href="/comprobantes/${data.pdf_file}"
                           target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline inline-flex items-center">
                            <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                        </a>
                    `);
                }

                // Limpiar el input de archivo
                pdfInput.val('');
                row.find('.pdf-name').text('');
            } else {
                showAlert(data.message || 'Error al actualizar el comprobante', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al conectar con el servidor', 'danger');
        })
        .finally(() => {
            // Restaurar el botón
            btn.removeClass('loading-btn opacity-70 cursor-not-allowed').html('<i class="fas fa-save mr-1"></i> Guardar').prop('disabled', false);
        });
    });

    // Mostrar alertas adaptadas a modo claro/oscuro
    function showAlert(message, type) {
        const alertHTML = `
            <div class="bg-${type === 'success' ? 'green-100 dark:bg-green-900/30' : 'red-100 dark:bg-red-900/30'} border border-${type === 'success' ? 'green' : 'red'}-500 text-${type === 'success' ? 'green-700 dark:text-green-400' : 'red-700 dark:text-red-400'} px-4 py-3 rounded-lg flex items-center mb-4 animate-fadeIn">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                ${message}
            </div>
        `;

        $('#alert-container').html(alertHTML);

        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            $('#alert-container').html('');
        }, 5000);
    }
});
</script>
@endpush
@endsection
