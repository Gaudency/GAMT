@extends('admin.master')
@section('content')
<div class="container mx-auto px-4 mt-24">
    <div class="flex justify-center">
        <div class="w-full lg:w-5/6">
            <div class="bg-gray-900/95 backdrop-blur-lg rounded-xl p-8 my-5 shadow-2xl relative">
                <div class="text-center mb-8 sticky top-0 z-50 bg-gray-900/95 backdrop-blur-lg py-4 rounded-t-xl">
                    <h2 class="text-gray-200 text-3xl flex items-center justify-center gap-3">
                        <i class="fas fa-file-invoice text-blue-400"></i> Préstamo de Comprobantes
                    </h2>
                    <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-200 transition-colors duration-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Información del libro/carpeta -->
                <div class="bg-blue-900/10 border border-blue-600 p-5 rounded-lg mb-6 text-gray-200">
                    <h3 class="text-blue-400 text-xl mb-4 pb-2 border-b border-white/10 flex items-center gap-2">
                        <i class="fas fa-folder-open"></i> Información de la Carpeta
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-400">Título:</span>
                            <span class="font-medium">{{ $book->title }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-400">Código:</span>
                            <span class="font-medium">{{ $book->N_codigo }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-400">Codigo Fisico:</span>
                            <span class="font-medium">{{ $book->tomo }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-400">Año:</span>
                            <span class="font-medium">{{ $book->year }}</span>
                        </div>
                        <div class="flex-16 flex-col">
                            <span class="text-sm text-gray-400">Comprobantes Disponibles:</span>
                            <span class="bg-green-500 text-white font-semibold px-2.5 py-0.5 rounded-full text-sm inline-flex items-center justify-center w-min">
                                {{ $comprobantes_disponibles->count() }}
                            </span>
                        </div>
                    </div>
                        </div>

                <!-- Navegación -->
                <div class="mb-4">
                    <a href="{{ url('show_book') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white rounded-full transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium">
                        <i class="fas fa-arrow-left"></i> Volver a Carpetas
                    </a>
                </div>

                <!-- Alertas -->
                <div id="alertContainer"></div>

                <!-- Formulario de préstamo -->
                <div class="mt-6">
                        <form id="loanForm" method="POST" action="{{ route('document.loan.store') }}">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">

                        <div class="bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-800/70">
                            <h4 class="text-gray-200 text-lg font-semibold border-b border-white/10 pb-3 mb-5 flex items-center gap-2">
                                <i class="fas fa-user"></i> Información del Solicitante
                            </h4>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <div class="mb-2">
                                        <label for="applicant_name" class="block text-gray-200 mb-2 font-medium">Nombre del Solicitante</label>
                                <input type="text"
                                               id="applicant_name"
                                       name="applicant_name"
                                               class="w-full px-4 py-2.5 bg-gray-800/70 border border-white/10 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                               required>
                                        <div class="text-red-500 text-sm mt-1 hidden">Por favor ingrese el nombre del solicitante</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-800/70">
                            <h4 class="text-gray-200 text-lg font-semibold border-b border-white/10 pb-3 mb-5 flex items-center gap-2">
                                <i class="fas fa-layer-group"></i> Tipo de Préstamo
                            </h4>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <div class="loan-type-selector">
                                        <div class="flex flex-wrap gap-5">
                                            <div class="flex-1 min-w-[200px]">
                                                <input type="radio"
                                                       id="tipoCompleto"
                                                       name="tipo_prestamo"
                                                       value="completo"
                                                       class="hidden"
                                                       checked>
                                                <label for="tipoCompleto" class="flex items-center gap-4 p-4 bg-gray-800/70 border border-white/10 rounded-lg cursor-pointer transition-all duration-300 hover:bg-gray-800/90 hover:-translate-y-1 h-full peer-checked:bg-blue-900/20 peer-checked:border-blue-500">
                                                    <i class="fas fa-folder text-2xl text-gray-400 peer-checked:text-blue-400"></i>
                                                    <div>
                                                        <span class="block font-medium text-gray-200 mb-1">Carpeta Completa</span>
                                                        <span class="block text-sm text-gray-400">Préstamo de todos los comprobantes disponibles</span>
                                                    </div>
                                </label>
                            </div>

                                            <div class="flex-1 min-w-[200px]">
                                                <input type="radio"
                                                       id="tipoParcial"
                                                       name="tipo_prestamo"
                                                       value="parcial"
                                                       class="hidden">
                                                <label for="tipoParcial" class="flex items-center gap-4 p-4 bg-gray-800/70 border border-white/10 rounded-lg cursor-pointer transition-all duration-300 hover:bg-gray-800/90 hover:-translate-y-1 h-full peer-checked:bg-blue-900/20 peer-checked:border-blue-500">
                                                    <i class="fas fa-file-alt text-2xl text-gray-400 peer-checked:text-blue-400"></i>
                                                    <div>
                                                        <span class="block font-medium text-gray-200 mb-1">Comprobantes Individuales</span>
                                                        <span class="block text-sm text-gray-400">Seleccionar comprobantes específicos</span>
                                                </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-800/70 hidden" id="comprobantesSelector">
                            <div class="bg-gray-800/95 backdrop-blur-lg z-10 pb-4">
                                <h4 class="text-gray-200 text-lg font-semibold border-b border-white/10 pb-3 mb-5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-receipt"></i> Selección de Comprobantes
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-900/20 text-blue-400 rounded-full text-sm">
                                        <span id="selectedCount">0</span> seleccionados
                                    </span>
                                </h4>

                                <div class="mb-4">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text"
                                               id="comprobanteSearch"
                                               class="w-full pl-10 pr-4 py-2.5 bg-gray-800/70 border border-white/10 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                               placeholder="Buscar comprobante por número...">
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <button type="button" id="selectAll" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800/70 border border-white/10 rounded-full text-gray-200 transition-all duration-300 hover:bg-green-900/20 hover:border-green-500 hover:text-green-400">
                                        <i class="fas fa-check-double"></i> Seleccionar Todos
                                    </button>
                                    <button type="button" id="deselectAll" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800/70 border border-white/10 rounded-full text-gray-200 transition-all duration-300 hover:bg-red-900/20 hover:border-red-500 hover:text-red-400">
                                        <i class="fas fa-times"></i> Deseleccionar Todos
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                @forelse($comprobantes_disponibles as $comprobante)
                                    <div class="comprobante-item" data-numero="{{ $comprobante->numero_comprobante }}">
                                        <input type="checkbox"
                                               name="comprobantes[]"
                                               value="{{ $comprobante->id }}"
                                               id="comp_{{ $comprobante->id }}"
                                               class="comprobante-checkbox hidden">
                                        <label for="comp_{{ $comprobante->id }}" class="block p-4 bg-gray-800/70 border border-white/10 rounded-lg cursor-pointer transition-all duration-300 hover:bg-gray-800/90 hover:-translate-y-1 h-full">
                                            <div class="text-blue-400 font-bold mb-2 text-lg">
                                                <i class="fas fa-receipt"></i> #{{ $comprobante->numero_comprobante }}
                                            </div>
                                            <div class="flex justify-between mb-2 text-sm text-gray-400">
                                                <span><i class="fas fa-file-alt"></i> {{ $comprobante->n_hojas }} hojas</span>
                                                @if($comprobante->pdf_file)
                                                    <span class="bg-red-900/20 text-red-400 px-2 py-0.5 rounded-full text-xs">
                                                        <i class="fas fa-file-pdf"></i> PDF
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-200 truncate">
                                                {{ Str::limit($comprobante->descripcion, 50) ?: 'Sin descripción' }}
                                            </div>
                                        </label>
                                        <!-- Contenedor para la observación de préstamo -->
                                        <div class="mt-2 observacion-prestamo-container hidden">
                                            <label for="obs_prestamo_{{ $comprobante->id }}" class="text-xs text-gray-400 block mb-1">Observación Préstamo (opcional):</label>
                                            <textarea name="observaciones_prestamo[{{ $comprobante->id }}]"
                                                      id="obs_prestamo_{{ $comprobante->id }}"
                                                      rows="2"
                                                      class="w-full px-3 py-1.5 text-sm rounded-md border border-white/10 bg-gray-800/90 text-gray-200 focus:ring-blue-500/50 focus:border-blue-500/50"
                                                      placeholder="Añadir observación para este comprobante..."></textarea>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-8 text-gray-400 italic">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        No hay comprobantes disponibles para préstamo
                                    </div>
                                @endforelse
                            </div>

                            <div class="text-red-500 text-sm mt-2 text-center hidden">
                                Por favor seleccione al menos un comprobante
                            </div>
                        </div>

                        <div class="bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-800/70">
                            <h4 class="text-gray-200 text-lg font-semibold border-b border-white/10 pb-3 mb-5 flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i> Información del Préstamo
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="mb-2">
                                        <label for="fecha_devolucion" class="block text-gray-200 mb-2 font-medium">Fecha de Devolución</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <i class="fas fa-calendar text-gray-400"></i>
                                            </div>
                                            <input type="date"
                                                   id="fecha_devolucion"
                                                   name="fecha_devolucion"
                                                   class="w-full pl-10 pr-4 py-2.5 bg-gray-800/70 border border-white/10 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                   required>
                                        </div>
                                        <div class="text-red-500 text-sm mt-1 hidden">Seleccione una fecha futura</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-2">
                                        <label for="description" class="block text-gray-200 mb-2 font-medium">Descripción/Observaciones (Opcional)</label>
                                        <textarea id="description"
                                                  name="description"
                                                  class="w-full px-4 py-2.5 bg-gray-800/70 border border-white/10 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                                  rows="3"
                                                  placeholder="Ingrese observaciones sobre el préstamo"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-8">
                            <button type="submit" id="submitBtn" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 text-white rounded-full shadow-lg hover:-translate-y-1 hover:shadow-xl transition-all duration-300 font-medium min-w-[200px]">
                                <i class="fas fa-save"></i> Registrar Préstamo
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
        </div>
    </div>

@push('styles')
<style>
    /* Animación para alertas */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in-out;
    }

    /* Estilos para comprobantes seleccionados */
    .comprobante-checkbox:checked + label {
        @apply bg-blue-900/20 border-blue-500;
    }

    /* Estilo para comprobantes al pasar el ratón */
    .comprobante-item label:hover {
        @apply bg-gray-800/90 -translate-y-1;
    }

    /* Estilo para errores */
    .is-invalid {
        @apply border-red-500 ring-1 ring-red-500;
    }
</style>
@endpush

@push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const loanForm = document.getElementById('loanForm');
    const tipoCompleto = document.getElementById('tipoCompleto');
    const tipoParcial = document.getElementById('tipoParcial');
        const comprobantesSelector = document.getElementById('comprobantesSelector');
    const comprobanteSearch = document.getElementById('comprobanteSearch');
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');
    const selectedCountEl = document.getElementById('selectedCount');
    const comprobanteItems = document.querySelectorAll('.comprobante-item');
    const comprobanteCheckboxes = document.querySelectorAll('.comprobante-checkbox');
    const submitBtn = document.getElementById('submitBtn');
    const alertContainer = document.getElementById('alertContainer');

    // Función para mostrar alertas
    function showAlert(message, type) {
        const alertHTML = `
            <div class="bg-${type === 'success' ? 'green' : 'red'}-900/10 border border-${type === 'success' ? 'green' : 'red'}-500 text-${type === 'success' ? 'green' : 'red'}-500 px-4 py-3 rounded-lg flex items-center mb-5 animate-fadeIn">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                ${message}
            </div>
        `;

        alertContainer.innerHTML = alertHTML;

        // Auto ocultar después de 5 segundos
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 5000);
    }

    // Función para contar y actualizar comprobantes seleccionados
    function updateSelectedCount() {
        const count = document.querySelectorAll('.comprobante-checkbox:checked').length;
        selectedCountEl.textContent = count;
    }

    // Cambiar tipo de préstamo
    function toggleComprobantesSelector() {
        if (tipoParcial.checked) {
            comprobantesSelector.classList.remove('hidden');
            comprobantesSelector.style.display = 'block';
            // Asegurar que el contenedor sea visible
            comprobantesSelector.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            comprobantesSelector.classList.add('hidden');
            comprobantesSelector.style.display = 'none';
        }
    }

    // Evento para cambios en tipo de préstamo
    tipoCompleto.addEventListener('change', toggleComprobantesSelector);
    tipoParcial.addEventListener('change', toggleComprobantesSelector);

    // Buscar comprobantes
    comprobanteSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();

        comprobanteItems.forEach(item => {
            const numero = item.dataset.numero.toLowerCase();
            if (searchTerm === '' || numero.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Seleccionar todos los comprobantes
    selectAllBtn.addEventListener('click', function() {
        const visibleItems = Array.from(comprobanteItems).filter(item =>
            item.style.display !== 'none'
        );

        visibleItems.forEach(item => {
            item.querySelector('.comprobante-checkbox').checked = true;
            // Mostrar campo de observación para los seleccionados
            const obsContainer = item.querySelector('.observacion-prestamo-container');
            if (obsContainer) obsContainer.classList.remove('hidden');
        });

        updateSelectedCount();
    });

    // Deseleccionar todos los comprobantes
    deselectAllBtn.addEventListener('click', function() {
        comprobanteCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
            // Ocultar campo de observación para los deseleccionados
            const item = checkbox.closest('.comprobante-item');
            const obsContainer = item.querySelector('.observacion-prestamo-container');
            if (obsContainer) {
                obsContainer.classList.add('hidden');
                obsContainer.querySelector('textarea').value = ''; // Limpiar al desmarcar
            }
        });

        updateSelectedCount();
    });

    // Actualizar contador y mostrar/ocultar observación cuando se selecciona/deselecciona un comprobante individual
    comprobanteCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            const item = this.closest('.comprobante-item');
            const obsContainer = item.querySelector('.observacion-prestamo-container');
            if (this.checked) {
                obsContainer.classList.remove('hidden');
            } else {
                obsContainer.classList.add('hidden');
                obsContainer.querySelector('textarea').value = ''; // Limpiar al desmarcar
            }
        });
    });

    // Validar y enviar formulario
    loanForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;

        // Validar nombre del solicitante
        const applicantName = document.getElementById('applicant_name');
        const applicantFeedback = applicantName.nextElementSibling;
        if (!applicantName.value.trim()) {
            applicantName.classList.add('is-invalid');
            applicantFeedback.classList.remove('hidden');
            isValid = false;
        } else {
            applicantName.classList.remove('is-invalid');
            applicantFeedback.classList.add('hidden');
        }

        // Validar fecha de devolución
        const fechaDevolucion = document.getElementById('fecha_devolucion');
        const fechaFeedback = fechaDevolucion.parentElement.nextElementSibling;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(fechaDevolucion.value);

        if (!fechaDevolucion.value || selectedDate <= today) {
            fechaDevolucion.classList.add('is-invalid');
            fechaFeedback.classList.remove('hidden');
            isValid = false;
        } else {
            fechaDevolucion.classList.remove('is-invalid');
            fechaFeedback.classList.add('hidden');
        }

        // Validar selección de comprobantes si el tipo es parcial
        if (tipoParcial.checked) {
            const checkedCount = document.querySelectorAll('.comprobante-checkbox:checked').length;
            const comprobanteError = document.querySelector('#comprobantesSelector > div:last-child');
            if (checkedCount === 0) {
                comprobanteError.classList.remove('hidden');
                isValid = false;
            } else {
                comprobanteError.classList.add('hidden');
            }
        }

        if (!isValid) {
            return;
        }

        // Desactivar botón de envío y mostrar cargando
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        // Enviar formulario con AJAX
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message || 'Préstamo registrado exitosamente', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect || '';
                }, 1500);
            } else {
                showAlert(data.message || 'Ocurrió un error al registrar el préstamo', 'danger');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Registrar Préstamo';
            }
        })
        .catch(error => {
            showAlert('Error al conectar con el servidor', 'danger');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Registrar Préstamo';
        });
    });

    // Establecer fecha mínima en el selector de fecha
    const fechaDevolucion = document.getElementById('fecha_devolucion');
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    fechaDevolucion.min = tomorrow.toISOString().split('T')[0];
    });
    </script>
@endpush
@endsection
