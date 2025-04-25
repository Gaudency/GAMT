@extends('admin.master')
@section('content')
<div class="w-full pt-24 pb-6 px-4">
    <div class="flex justify-center">
        <div class="w-full lg:w-3/4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 dark:from-red-600 dark:to-pink-700 text-white p-4">
                    <h1 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-edit mr-2"></i> Editar Comprobante #{{ $comprobante->numero_comprobante }}
                    </h1>
                </div>

                <div class="p-6">
                    <!-- Navegación -->
                    <a href="{{ route('books.comprobantes.index', $comprobante->book_id) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-400/80 to-gray-600/80 hover:from-gray-500 hover:to-gray-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium mb-6 shine-button">
                        <i class="fas fa-arrow-left mr-2"></i> Volver a la lista
                    </a>

                    <!-- Información de la carpeta -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 p-5 rounded-lg mb-6">
                        <h3 class="text-gray-800 dark:text-blue-400 text-lg mb-4 pb-2 border-b border-gray-200 dark:border-gray-600 flex items-center">
                            <i class="fas fa-folder-open mr-2"></i> Información de la Carpeta
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Título:</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $comprobante->book->title }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Código Digital:</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $comprobante->book->N_codigo }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Codigo fisico:</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $comprobante->book->tomo }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Año:</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $comprobante->book->year }}</span>
                            </div>
                        </div>
                    </div>

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
                    <form action="{{ route('books.comprobantes.update', [$comprobante->book_id, $comprobante->id]) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="editComprobanteForm">
                        @csrf
                        @method('PUT')

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 mb-6 transition-all duration-300 border border-gray-200 dark:border-gray-600">
                            <h4 class="text-gray-800 dark:text-gray-200 text-lg font-semibold border-b border-gray-200 dark:border-gray-600 pb-3 mb-5 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Información Básica
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label for="codigo_personalizado" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium flex items-center">
                                        <i class="fas fa-barcode mr-2"></i> Código Personalizado
                                    </label>
                                    <input type="text"
                                           class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('codigo_personalizado') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                           id="codigo_personalizado"
                                           name="codigo_personalizado"
                                           value="{{ old('codigo_personalizado', $comprobante->codigo_personalizado) }}"
                                           placeholder="Ej: 1234589N°1">
                                    @error('codigo_personalizado')
                                        <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2">
                                        Código alternativo o adicional para identificar el comprobante
                                    </small>
                                </div>

                                <div>
                                    <label for="costo" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium flex items-center">
                                        <i class="fas fa-coins mr-2"></i> Costo / Devengado
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
                                               value="{{ old('costo', $comprobante->costo) }}"
                                               placeholder="0.00">
                                    </div>
                                    @error('costo')
                                        <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2">
                                        Valor monetario asociado al comprobante
                                    </small>
                                </div>

                                <div>
                                    <label for="n_hojas" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium flex items-center">
                                        <i class="fas fa-file-alt mr-2"></i> Número de Hojas
                            </label>
                            <input type="number"
                                           class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('n_hojas') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                   id="n_hojas"
                                   name="n_hojas"
                                           value="{{ old('n_hojas', $comprobante->n_hojas) }}"
                                   required
                                   min="0">
                                    @error('n_hojas')
                                        <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="estado" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium flex items-center">
                                        <i class="fas fa-toggle-on mr-2"></i> Estado
                                    </label>
                                    <select class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('estado') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                            id="estado"
                                            name="estado">
                                        <option value="activo" {{ old('estado', $comprobante->estado) == 'activo' ? 'selected' : '' }}>
                                            Activo (Disponible)
                                        </option>
                                        <option value="inactivo" {{ old('estado', $comprobante->estado) == 'inactivo' ? 'selected' : '' }}>
                                            Inactivo (Prestado)
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2 flex items-center">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        El estado "Inactivo" se usa para comprobantes que están prestados o no disponibles.
                                    </small>
                                </div>
                        </div>

                            <div>
                                <label for="descripcion" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium flex items-center">
                                    <i class="fas fa-align-left mr-2"></i> Descripción
                            </label>
                                <div class="flex flex-col">
                                    <textarea class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('descripcion') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300 min-h-[100px]"
                                          id="descripcion"
                                              name="descripcion">{{ old('descripcion', $comprobante->descripcion) }}</textarea>

                                    <!-- Botón de dictado por voz para comprobantes -->
                                    <div class="mt-2 flex justify-end">
                                        <button type="button" id="dictateButton" class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 flex items-center">
                                            <i class="fas fa-microphone mr-2"></i><span>Dictar</span>
                                        </button>
                                        <div id="dictationStatus" class="ml-3 hidden items-center text-sm text-green-500">
                                            <i class="fas fa-circle-notch fa-spin mr-2"></i>
                                            <span>Escuchando...</span>
                                        </div>
                                    </div>
                                </div>
                                @error('descripcion')
                                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 mb-6 transition-all duration-300 border border-gray-200 dark:border-gray-600">
                            <h4 class="text-gray-800 dark:text-gray-200 text-lg font-semibold border-b border-gray-200 dark:border-gray-600 pb-3 mb-5 flex items-center">
                                <i class="fas fa-file-pdf mr-2"></i> Documento PDF
                            </h4>

                            <!-- PDF Actual -->
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-200 mb-2 font-medium flex items-center">
                                    <i class="fas fa-file-pdf mr-2"></i> PDF Actual
                            </label>
                            @if($comprobante->pdf_file)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center gap-4">
                                            <i class="fas fa-file-pdf text-4xl text-red-500"></i>
                                            <div class="flex-1">
                                                <span class="block text-gray-700 dark:text-gray-200 mb-1 break-all">{{ basename($comprobante->pdf_file) }}</span>
                                                <div class="flex flex-wrap gap-4">
                                    <a href="{{ asset('comprobantes/'.$comprobante->pdf_file) }}"
                                       target="_blank"
                                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg text-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-sm shine-button">
                                                        <i class="fas fa-eye mr-1"></i> Ver PDF
                                                    </a>
                                                    <button type="button"
                                                            class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-400/80 to-pink-600/80 hover:from-red-500 hover:to-pink-700 text-white rounded-lg text-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-sm shine-button"
                                                            data-toggle="modal"
                                                            data-target="#confirmRemoveModal"
                                                            onclick="document.getElementById('confirmRemoveModal').classList.remove('hidden')">
                                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar PDF
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            @else
                                    <div class="text-gray-500 dark:text-gray-400 italic p-2">
                                        <i class="fas fa-info-circle mr-2"></i> No hay PDF adjunto
                                    </div>
                            @endif
                        </div>

                            <!-- Subir nuevo PDF -->
                            <div>
                                <label for="pdf_file" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium flex items-center">
                                    <i class="fas fa-upload mr-2"></i> Subir nuevo PDF
                            </label>
                            <input type="file"
                                       class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border @error('pdf_file') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"
                                   id="pdf_file"
                                   name="pdf_file"
                                   accept=".pdf">
                                @error('pdf_file')
                                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <small class="block text-gray-500 dark:text-gray-400 text-xs mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Dejar en blanco para mantener el PDF actual. Máximo 50MB.
                            </small>

                                <!-- Vista previa del nombre del archivo seleccionado -->
                                <div id="selectedFileName" class="hidden mt-3 bg-white dark:bg-gray-800 p-3 rounded-lg flex items-center gap-2 text-gray-700 dark:text-gray-200 animate-fadeIn border border-gray-200 dark:border-gray-600">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Botón de guardar -->
                        <div class="text-center mt-8">
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-400/80 to-indigo-600/80 hover:from-blue-500 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg font-medium min-w-[200px] shine-button" id="submitBtn">
                                <i class="fas fa-save mr-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar eliminación de PDF -->
<div id="confirmRemoveModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-auto shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center"><i class="fas fa-trash-alt mr-2"></i> Confirmar eliminación</h5>
                <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="document.getElementById('confirmRemoveModal').classList.add('hidden')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 text-gray-700 dark:text-gray-300">
                <p>¿Está seguro de que desea eliminar el PDF actual? Esta acción no se puede deshacer.</p>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-400/80 to-gray-600/80 hover:from-gray-500 hover:to-gray-700 text-white rounded-lg transition-all duration-300 shine-button" onclick="document.getElementById('confirmRemoveModal').classList.add('hidden')">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </button>
                <form action="{{ route('books.comprobantes.remove-pdf', [$comprobante->book_id, $comprobante->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-400/80 to-pink-600/80 hover:from-red-500 hover:to-pink-700 text-white rounded-lg transition-all duration-300 shine-button">
                        <i class="fas fa-trash-alt mr-2"></i> Eliminar PDF
                    </button>
                </form>
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
        animation: fadeIn 0.3s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editComprobanteForm');
        const submitBtn = document.getElementById('submitBtn');
        const pdfInput = document.getElementById('pdf_file');
        const selectedFileName = document.getElementById('selectedFileName');

        // Añadir clase shine-button a todos los elementos con esa clase
        document.querySelectorAll('.shine-button').forEach(element => {
            element.classList.add('shine-button');
        });

        // Mostrar nombre del archivo seleccionado
        pdfInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                selectedFileName.classList.remove('hidden');
                selectedFileName.classList.add('flex');
                selectedFileName.querySelector('span').textContent = this.files[0].name;
            } else {
                selectedFileName.classList.add('hidden');
                selectedFileName.classList.remove('flex');
            }
        });

        // Mostrar indicador de carga al enviar el formulario
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';
        });
    });
</script>

<!-- JavaScript para dictado de voz en comprobantes -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dictateButton = document.getElementById('dictateButton');
    const dictationStatus = document.getElementById('dictationStatus');
    const descripcionField = document.getElementById('descripcion');

    // Verificar soporte para SpeechRecognition
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        console.error('Tu navegador no soporta el reconocimiento de voz.');
        dictateButton.disabled = true;
        dictateButton.classList.add('bg-gray-400');
        dictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
        dictateButton.title = 'Reconocimiento de voz no disponible en este navegador';
        return;
    }

    // Crear instancia de reconocimiento de voz
    const recognition = new SpeechRecognition();
    recognition.lang = 'es-ES';
    recognition.continuous = true;
    recognition.interimResults = true;

    let isListening = false;

    // Función para iniciar/detener dictado
    function toggleDictation() {
        if (isListening) {
            // Detener reconocimiento
            recognition.stop();
            isListening = false;
            dictateButton.innerHTML = '<i class="fas fa-microphone mr-2"></i><span>Dictar</span>';
            dictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
            dictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
            dictationStatus.classList.add('hidden');
            dictationStatus.classList.remove('flex');
            console.log('Dictado detenido');
        } else {
            // Iniciar reconocimiento
            try {
                recognition.start();
                isListening = true;
                dictateButton.innerHTML = '<i class="fas fa-stop-circle mr-2"></i><span>Detener</span>';
                dictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
                dictateButton.classList.add('bg-red-500', 'hover:bg-red-600');
                dictationStatus.classList.remove('hidden');
                dictationStatus.classList.add('flex');
                console.log('Dictado iniciado');
            } catch (error) {
                console.error('Error al iniciar el reconocimiento de voz:', error);
                alert('No se pudo iniciar el reconocimiento de voz. ' + error.message);
            }
        }
    }

    // Asignar evento al botón
    dictateButton.addEventListener('click', toggleDictation);

    // Manejar resultados del reconocimiento
    recognition.onresult = function(event) {
        const results = event.results;
        const currentText = descripcionField.value;

        // Obtener la transcripción actual
        let transcript = '';
        for (let i = event.resultIndex; i < results.length; i++) {
            if (results[i].isFinal) {
                transcript += results[i][0].transcript + ' ';
            }
        }

        // Si tenemos texto nuevo, añadirlo al campo
        if (transcript) {
            console.log('Texto reconocido:', transcript);

            // Si el campo ya tiene texto, añadir espacio y el nuevo texto
            if (currentText && currentText.trim() !== '') {
                // Verificar si el texto actual termina con un signo de puntuación
                if (currentText.trim().match(/[.!?]$/)) {
                    descripcionField.value = currentText + ' ' + transcript.charAt(0).toUpperCase() + transcript.slice(1);
                } else {
                    descripcionField.value = currentText + ' ' + transcript;
                }
            } else {
                // Campo vacío, comenzar con mayúscula
                descripcionField.value = transcript.charAt(0).toUpperCase() + transcript.slice(1);
            }
        }
    };

    // Manejar errores
    recognition.onerror = function(event) {
        console.error('Error de reconocimiento:', event.error);

        // Detener el dictado y restablecer la interfaz
        isListening = false;
        dictateButton.innerHTML = '<i class="fas fa-microphone mr-2"></i><span>Dictar</span>';
        dictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
        dictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
        dictationStatus.classList.add('hidden');
        dictationStatus.classList.remove('flex');

        // Mostrar mensaje específico según el error
        if (event.error === 'not-allowed') {
            alert('No se ha concedido permiso para usar el micrófono. Verifica la configuración de tu navegador.');
        } else if (event.error === 'no-speech') {
            // Este es común y no requiere alerta
            console.warn('No se detectó ninguna voz');
        } else {
            alert('Error en el reconocimiento de voz: ' + event.error);
        }
    };

    // Cuando el reconocimiento termina por cualquier razón (excepto stop() manual)
    recognition.onend = function() {
        // Si seguimos en modo escucha pero el reconocimiento terminó
        // (puede ocurrir por tiempo de espera), reiniciarlo
        if (isListening) {
            recognition.start();
            console.log('Reconocimiento reiniciado automáticamente');
        }
    };
});
</script>
@endpush
@endsection
