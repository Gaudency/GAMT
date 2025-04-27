@extends('admin.master')
@section('content')
<div class="w-full pt-24 pb-6 px-4">
    <div class="flex justify-center">
        <div class="w-full max-w-6xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 dark:from-red-600 dark:to-pink-700 text-white p-4">
                    <h1 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-receipt mr-2"></i> Añadir Comprobantes
                    </h1>
                </div>

                <div class="p-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 p-5 rounded-lg mb-6">
                        <h3 class="text-gray-800 dark:text-blue-400 text-lg mb-4 pb-2 border-b border-gray-200 dark:border-gray-600 flex items-center">
                            <i class="fas fa-folder-open mr-2"></i> Información de la Carpeta
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Título</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->title }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Código Digital</span>
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">Categoría</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $book->category->cat_title }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para volver -->
                    <a href="{{ route('books.comprobantes.index', $book->id) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-400/80 to-gray-600/80 hover:from-gray-500 hover:to-gray-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium mb-6 shine-button">
                        <i class="fas fa-arrow-left mr-2"></i> Volver a Comprobantes
                    </a>

                    <!-- Mensajes de error -->
                    @if($errors->any())
                        <div class="bg-red-100 dark:bg-red-900/30 border border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
                            <h5 class="flex items-center font-medium"><i class="fas fa-exclamation-triangle mr-2"></i> Errores encontrados:</h5>
                            <ul class="mt-2 ml-6 list-disc">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form action="{{ route('books.comprobantes.store', $book->id) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="createComprobantesForm">
                        @csrf

                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-5 mb-6 transition-all duration-300">
                            <h4 class="text-gray-800 dark:text-gray-200 text-lg font-semibold border-b border-gray-200 dark:border-gray-600 pb-3 mb-5 flex items-center">
                                <i class="fas fa-sort-numeric-down mr-2"></i> Rango de Comprobantes
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label for="comprobante_inicio" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">
                                        Comprobante Inicial
                                    </label>
                                    <input type="number"
                                           class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('comprobante_inicio') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                           id="comprobante_inicio"
                                           name="comprobante_inicio"
                                           required
                                           min="1"
                                           value="{{ $inicio ?? old('comprobante_inicio') }}">
                                    @error('comprobante_inicio')
                                        <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="comprobante_fin" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">
                                        Comprobante Final
                                    </label>
                                    <input type="number"
                                           class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('comprobante_fin') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                           id="comprobante_fin"
                                           name="comprobante_fin"
                                           required
                                           min="1"
                                           value="{{ $fin ?? old('comprobante_fin') }}">
                                    @error('comprobante_fin')
                                        <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-blue-600 dark:text-blue-400 text-lg text-center py-3 px-4 bg-blue-100/80 dark:bg-blue-900/30 rounded-lg font-medium transition-all duration-300" id="totalComprobantes">
                                <i class="fas fa-calculator mr-2"></i> Total de comprobantes a crear: <span id="total">0</span>
                            </div>

                            <div class="bg-blue-100 dark:bg-blue-900/30 border border-blue-500 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-lg mt-4 hidden" id="existingComprobanteWarning">
                                <i class="fas fa-info-circle mr-2"></i> <span id="warningMessage"></span>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-5 mb-6 transition-all duration-300">
                            <h4 class="text-gray-800 dark:text-gray-200 text-lg font-semibold border-b border-gray-200 dark:border-gray-600 pb-3 mb-5 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Información Adicional
                            </h4>

                            <div class="mb-5">
                                <label for="codigo_personalizado" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">
                                    Código Personalizado (opcional)
                                </label>
                                <input type="text"
                                       class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('codigo_personalizado') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                       id="codigo_personalizado"
                                       name="codigo_personalizado"
                                       value="{{ old('codigo_personalizado') }}"
                                       placeholder="Ej: 1234589N°1">
                                <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2">
                                    Código alternativo o adicional para identificar el comprobante (solo se aplicará al primer comprobante)
                                </small>
                                @error('codigo_personalizado')
                                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <label for="costo" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">
                                    Costo / Devengado (opcional)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400">Bs</span>
                                    </div>
                                    <input type="number"
                                           class="w-full pl-10 px-4 py-2.5 bg-white dark:bg-gray-800 border @error('costo') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                           id="costo"
                                           name="costo"
                                           min="0"
                                           step="0.01"
                                           value="{{ old('costo') }}"
                                           placeholder="0.00">
                                </div>
                                <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2">
                                    Valor monetario asociado al comprobante (se aplicará a todos los comprobantes creados)
                                </small>
                                @error('costo')
                                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <label for="n_hojas" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">
                                    Número de Hojas (opcional)
                                </label>
                                <input type="number"
                                       class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('n_hojas') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                       id="n_hojas"
                                       name="n_hojas"
                                       min="0"
                                       value="{{ old('n_hojas') }}">
                                <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2">
                                    Si se especifica, todos los comprobantes se crearán con este número de hojas
                                </small>
                                @error('n_hojas')
                                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <label for="descripcion" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">
                                    Descripción (opcional)
                                </label>
                                <textarea class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('descripcion') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300 min-h-[100px]"
                                          id="descripcion"
                                          name="descripcion"
                                          placeholder="Ingrese una descripción para los comprobantes">{{ old('descripcion') }}</textarea>
                                <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2">
                                    Si se especifica, todos los comprobantes se crearán con esta descripción
                                </small>
                                @error('descripcion')
                                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="pdf_file" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">
                                    Archivo PDF (opcional)
                                </label>
                                <input type="file"
                                       class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('pdf_file') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                       id="pdf_file"
                                       name="pdf_file"
                                       accept=".pdf">
                                <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2">
                                    Si se sube un archivo, solo se asociará al primer comprobante del rango
                                </small>
                                @error('pdf_file')
                                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-8">
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium min-w-[200px] shine-button" id="submitBtn">
                                <i class="fas fa-save mr-2"></i> Crear Comprobantes
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

    /* Para el indicador de advertencia */
    .warning {
        @apply text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 !important;
    }

    /* Animaciones y transiciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Elementos del DOM ---
        const comprobanteInicio = document.getElementById('comprobante_inicio');
        const comprobanteFin = document.getElementById('comprobante_fin');
        // Corregido: Seleccionar el span con id="total"
        const totalSpan = document.getElementById('total');
        const totalContainer = document.getElementById('totalComprobantes'); // El div contenedor
        const warningContainer = document.getElementById('existingComprobanteWarning');
        const warningMessageSpan = document.getElementById('warningMessage');
        const submitBtn = document.getElementById('submitBtn');
        const bookId = {{ $book->id }}; // Obtener el ID del libro

        // --- Función para calcular total ---
        function calcularTotalComprobantes() {
            const inicio = parseInt(comprobanteInicio.value) || 0;
            const fin = parseInt(comprobanteFin.value) || 0;

            if (inicio > 0 && fin > 0 && fin >= inicio) {
                const total = fin - inicio + 1;
                // Corregido: Actualizar el contenido del span
                totalSpan.textContent = total;
                totalContainer.classList.remove('warning'); // Quitar estilo de advertencia si lo tenía
            } else {
                // Corregido: Actualizar el contenido del span
                totalSpan.textContent = '0';
                if (fin > 0 && inicio > 0 && fin < inicio) {
                    // Añadir estilo de advertencia si el rango es inválido
                    totalContainer.classList.add('warning');
                } else {
                    totalContainer.classList.remove('warning');
                }
            }
            // Llamar a la verificación de rango cada vez que se calcula el total
            checkExistingComprobantes();
        }

        // --- Función para verificar comprobantes existentes (NUEVO) ---
        let checkTimeout; // Para evitar llamadas múltiples muy rápidas
        function checkExistingComprobantes() {
            clearTimeout(checkTimeout); // Cancela el timeout anterior si existe
            const inicio = parseInt(comprobanteInicio.value) || 0;
            const fin = parseInt(comprobanteFin.value) || 0;

            // Ocultar advertencia y habilitar botón por defecto
            warningContainer.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            if (inicio > 0 && fin > 0 && fin >= inicio) {
                // Esperar un poco antes de hacer la llamada AJAX (ej. 500ms)
                checkTimeout = setTimeout(() => {
                    fetch(`/books/${bookId}/comprobantes/check-range?inicio=${inicio}&fin=${fin}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            // Si usas CSRF en GET, descomenta la siguiente línea y asegúrate que el token esté disponible
                            // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.exists) {
                            let message = `¡Advertencia! Ya existen ${data.count} comprobante(s) en este rango.`;
                            if (data.existing_numbers && data.existing_numbers.length > 0) {
                                // Mostrar solo los primeros 5 para no saturar
                                const limit = 5;
                                const displayNumbers = data.existing_numbers.slice(0, limit).join(', ');
                                message += ` (Ej: ${displayNumbers}${data.existing_numbers.length > limit ? '...' : ''})`;
                            }
                            warningMessageSpan.textContent = message;
                            warningContainer.classList.remove('hidden');
                            // Opcional: Deshabilitar el botón si existen comprobantes (ya que el backend lo impedirá)
                            // submitBtn.disabled = true;
                            // submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            warningContainer.classList.add('hidden');
                            submitBtn.disabled = false;
                             submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    })
                    .catch(error => {
                        console.error('Error al verificar rango:', error);
                        warningMessageSpan.textContent = 'No se pudo verificar el rango en el servidor.';
                        warningContainer.classList.remove('hidden');
                        // Decidir si deshabilitar o no en caso de error
                        // submitBtn.disabled = true;
                        // submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    });
                }, 500); // 500ms de espera
            } else {
                 // Si el rango no es válido, ocultar advertencia y deshabilitar (o no)
                 warningContainer.classList.add('hidden');
                 if (fin > 0 && inicio > 0 && fin < inicio) {
                     // submitBtn.disabled = true; // Deshabilitar si el rango es inválido
                     // submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                 } else {
                    // submitBtn.disabled = false;
                    // submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                 }
            }
        }


        // --- Event Listeners ---
        comprobanteInicio.addEventListener('input', calcularTotalComprobantes);
        comprobanteFin.addEventListener('input', calcularTotalComprobantes);

        // Calcular total inicial al cargar la página (si hay valores)
        calcularTotalComprobantes();

        // --- Remover código innecesario de categoría ---
        // (Se eliminó la lógica de categoriaSelect y comprobantesContainer)
    });
</script>
@endpush
@endsection
