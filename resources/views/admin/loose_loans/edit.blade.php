<!DOCTYPE html>
<html lang="es">
<head>
    @include('admin.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Para la firma digital -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        // Asegurarnos que el modo oscuro se aplica correctamente
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-full transition-colors duration-300">
    @include('admin.header')
    <div class="flex mt-16">
        @include('admin.sidebar')

        <div class="flex-1 p-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Préstamo Suelto</h1>
                </div>

                @if($errors->any())
                <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-4" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('loose-loans.update', $loan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="folder_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Carpeta</label>
                            <div class="mt-1 p-2 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                                {{ $loan->folder_code }}
                            </div>
                            <input type="hidden" name="folder_code" value="{{ $loan->folder_code }}">
                        </div>

                        <div>
                            <label for="book_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título del Libro</label>
                            <div class="relative">
                                <input type="text" name="book_title" id="book_title"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10"
                                    required value="{{ old('book_title', $loan->book_title) }}"
                                    style="text-transform: uppercase;"
                                    oninput="this.value = this.value.toUpperCase()">
                                <button type="button" id="titleDictateButton"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-purple-500 hover:bg-purple-600 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm transition-colors">
                                    <i class="fas fa-microphone"></i>
                                </button>
                            </div>
                            <div id="titleDictationStatus" class="hidden text-xs text-green-500 mt-1 flex items-center">
                                <i class="fas fa-circle-notch fa-spin mr-1"></i>
                                <span>Escuchando...</span>
                            </div>
                        </div>

                        <div>
                            <label for="sheets_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad de Hojas</label>
                            <input type="number" name="sheets_count" id="sheets_count" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500" required value="{{ old('sheets_count', $loan->sheets_count) }}">
                        </div>

                        <div>
                            <label for="lender_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Prestador</label>
                            <div class="relative">
                                <input type="text" name="lender_name" id="lender_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10" required value="{{ old('lender_name', $loan->lender_name) }}">
                                <button type="button" id="lenderDictateButton"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-purple-500 hover:bg-purple-600 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm transition-colors">
                                    <i class="fas fa-microphone"></i>
                                </button>
                            </div>
                            <div id="lenderDictationStatus" class="hidden text-xs text-green-500 mt-1 flex items-center">
                                <i class="fas fa-circle-notch fa-spin mr-1"></i>
                                <span>Escuchando...</span>
                            </div>
                        </div>

                        <div>
                            <label for="loan_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Préstamo</label>
                            <div class="mt-1 p-2 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i') }}
                            </div>
                            <input type="hidden" name="loan_date" value="{{ $loan->loan_date->format('Y-m-d H:i:s') }}">
                        </div>

                        <div>
                            <label for="return_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha y Hora de Devolución</label>
                            <input type="datetime-local" name="return_date" id="return_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500" required value="{{ old('return_date', $loan->return_date->format('Y-m-d\TH:i')) }}">
                        </div>

                        <!-- Campo de estado eliminado -->
                        <input type="hidden" name="status" value="{{ $loan->status }}">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $loan->description) }}</textarea>

                        <!-- Botón de dictado por voz para descripción -->
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Firma Digital</label>
                        <div class="border-2 border-gray-300 dark:border-gray-700 rounded-md">
                            <canvas id="signature-pad" class="w-full h-64 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800"></canvas>
                        </div>
                        <div class="flex justify-end mt-2 space-x-2">
                            <button type="button" id="clear-signature" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition duration-300">
                                Limpiar
                            </button>
                        </div>
                        <input type="hidden" name="digital_signature" id="digital_signature" value="{{ $loan->digital_signature }}">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Si la firma digital falla por ser demasiado grande, puede usar la opción de confirmación abajo.</p>

                        @if($loan->digital_signature)
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Firma Actual:</p>
                            <img src="{{ $loan->digital_signature }}" alt="Firma actual" class="max-w-full h-auto border border-gray-300 dark:border-gray-600 rounded-md">
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms_confirmation" name="terms_confirmation" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="1" checked required>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms_confirmation" class="font-medium text-gray-700 dark:text-gray-300">Confirmo que he leído y acepto los términos del préstamo</label>
                                <p class="text-gray-500 dark:text-gray-400">Esta confirmación es obligatoria. La firma digital es opcional.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('loose-loans.index') }}" class="bg-gradient-to-r from-gray-500 via-white to-gray-500 hover:from-gray-600 hover:to-gray-600 text-gray-600 font-medium py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">
                            <i class="fas fa-times-circle mr-2"></i>Cancelar
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600 font-medium py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">
                            <i class="fas fa-pen-to-square mr-2"></i>Actualizar Préstamo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.footer')

    <!-- FontAwesome para íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        // Inicializar el pad de firma
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Ajustar el tamaño del canvas
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // Limpiar después de cambiar el tamaño
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas(); // Inicializar el tamaño

        // Limpiar firma
        document.getElementById('clear-signature').addEventListener('click', function() {
            signaturePad.clear();
            document.getElementById('digital_signature').value = '';
        });

        // Al enviar el formulario, guardar la firma si se ha modificado
        document.querySelector('form').addEventListener('submit', function(e) {
            try {
                // Verificar si hay una firma existente
                const existingSignature = '{{ !empty($loan->digital_signature) }}' === '1';

                // Solo si se ha dibujado una nueva firma, actualizar el valor
                if (!signaturePad.isEmpty()) {
                    const signatureData = signaturePad.toDataURL('image/jpeg', 0.5);
                    document.getElementById('digital_signature').value = signatureData;
                    console.log('Nueva firma capturada');
                } else if (!existingSignature) {
                    // Si no hay firma ni anterior ni nueva, no pasa nada
                    console.log('No hay firma, pero no es obligatoria');
                }

                // Siempre verificamos que el checkbox esté marcado
                if (!document.getElementById('terms_confirmation').checked) {
                    e.preventDefault();
                    alert('Debe confirmar que ha leído y acepta los términos del préstamo');
                    return;
                }

                // Si todo está bien, dejamos que el formulario se envíe normalmente
            } catch (error) {
                e.preventDefault();
                console.error('Error al procesar la firma:', error);
                alert('Hubo un problema al procesar la firma. La firma no es obligatoria, pero debe marcar la casilla de confirmación.');
            }
        });

        // Ajustar al modo oscuro si es necesario
        if (document.documentElement.classList.contains('dark')) {
            signaturePad.penColor = 'rgb(255, 255, 255)';
        }
    </script>

    <!-- Script para dictado por voz -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables para dictado
        const dictateButton = document.getElementById('dictateButton');
        const dictationStatus = document.getElementById('dictationStatus');
        const descriptionField = document.getElementById('description');
        const titleDictateButton = document.getElementById('titleDictateButton');
        const titleDictationStatus = document.getElementById('titleDictationStatus');
        const titleField = document.getElementById('book_title');
        const lenderDictateButton = document.getElementById('lenderDictateButton');
        const lenderDictationStatus = document.getElementById('lenderDictationStatus');
        const lenderField = document.getElementById('lender_name');

        // Verificar soporte para SpeechRecognition
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (!SpeechRecognition) {
            console.error('Tu navegador no soporta el reconocimiento de voz.');
            if (dictateButton) {
                dictateButton.disabled = true;
                dictateButton.classList.add('bg-gray-400');
                dictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
                dictateButton.title = 'Reconocimiento de voz no disponible en este navegador';
            }
            if (titleDictateButton) {
                titleDictateButton.disabled = true;
                titleDictateButton.classList.add('bg-gray-400');
                titleDictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
                titleDictateButton.title = 'Reconocimiento de voz no disponible en este navegador';
            }
            if (lenderDictateButton) {
                lenderDictateButton.disabled = true;
                lenderDictateButton.classList.add('bg-gray-400');
                lenderDictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
                lenderDictateButton.title = 'Reconocimiento de voz no disponible en este navegador';
            }
            return;
        }

        // === Dictado para título ===
        if (titleDictateButton) {
            const titleRecognition = new SpeechRecognition();
            titleRecognition.lang = 'es-ES';
            titleRecognition.continuous = false; // Una sola frase para el título
            titleRecognition.interimResults = false;

            let isTitleListening = false;

            // Iniciar/detener dictado del título
            titleDictateButton.addEventListener('click', function() {
                if (isTitleListening) {
                    // Detener reconocimiento
                    titleRecognition.stop();
                    isTitleListening = false;
                    titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                    titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                    titleDictationStatus.classList.add('hidden');
                    titleDictationStatus.classList.remove('flex');
                    console.log('Dictado de título detenido');
                } else {
                    // Iniciar reconocimiento
                    try {
                        titleRecognition.start();
                        isTitleListening = true;
                        titleDictateButton.innerHTML = '<i class="fas fa-stop-circle"></i>';
                        titleDictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
                        titleDictateButton.classList.add('bg-red-500', 'hover:bg-red-600');
                        titleDictationStatus.classList.remove('hidden');
                        titleDictationStatus.classList.add('flex');
                        console.log('Dictado de título iniciado');
                    } catch (error) {
                        console.error('Error al iniciar el reconocimiento de voz para título:', error);
                        alert('No se pudo iniciar el reconocimiento de voz: ' + error.message);
                    }
                }
            });

            // Manejar resultados del reconocimiento de título
            titleRecognition.onresult = function(event) {
                const results = event.results;

                // Obtener la transcripción
                if (results.length > 0 && results[0].isFinal) {
                    let transcript = results[0][0].transcript;
                    console.log('Título reconocido:', transcript);

                    // Convertir a mayúsculas
                    transcript = transcript.toUpperCase();

                    // Establecer en el campo
                    titleField.value = transcript;
                }
            };

            // Manejar errores y finalización
            titleRecognition.onerror = function(event) {
                console.error('Error de reconocimiento de título:', event.error);
                isTitleListening = false;
                titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                titleDictationStatus.classList.add('hidden');
                titleDictationStatus.classList.remove('flex');
            };

            titleRecognition.onend = function() {
                isTitleListening = false;
                titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                titleDictationStatus.classList.add('hidden');
                titleDictationStatus.classList.remove('flex');
            };
        }

        // === Dictado para nombre del prestador ===
        if (lenderDictateButton) {
            const lenderRecognition = new SpeechRecognition();
            lenderRecognition.lang = 'es-ES';
            lenderRecognition.continuous = false;
            lenderRecognition.interimResults = false;

            let isLenderListening = false;

            // Iniciar/detener dictado del nombre del prestador
            lenderDictateButton.addEventListener('click', function() {
                if (isLenderListening) {
                    // Detener reconocimiento
                    lenderRecognition.stop();
                    isLenderListening = false;
                    lenderDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                    lenderDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    lenderDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                    lenderDictationStatus.classList.add('hidden');
                    lenderDictationStatus.classList.remove('flex');
                    console.log('Dictado de prestador detenido');
                } else {
                    // Iniciar reconocimiento
                    try {
                        lenderRecognition.start();
                        isLenderListening = true;
                        lenderDictateButton.innerHTML = '<i class="fas fa-stop-circle"></i>';
                        lenderDictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
                        lenderDictateButton.classList.add('bg-red-500', 'hover:bg-red-600');
                        lenderDictationStatus.classList.remove('hidden');
                        lenderDictationStatus.classList.add('flex');
                        console.log('Dictado de prestador iniciado');
                    } catch (error) {
                        console.error('Error al iniciar el reconocimiento de voz para prestador:', error);
                        alert('No se pudo iniciar el reconocimiento de voz: ' + error.message);
                    }
                }
            });

            // Manejar resultados del reconocimiento de prestador
            lenderRecognition.onresult = function(event) {
                const results = event.results;

                // Obtener la transcripción
                if (results.length > 0 && results[0].isFinal) {
                    let transcript = results[0][0].transcript;
                    console.log('Prestador reconocido:', transcript);

                    // Capitalize each word in the name
                    transcript = transcript.split(' ')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                        .join(' ');

                    // Establecer en el campo
                    lenderField.value = transcript;
                }
            };

            // Manejar errores y finalización
            lenderRecognition.onerror = function(event) {
                console.error('Error de reconocimiento de prestador:', event.error);
                isLenderListening = false;
                lenderDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                lenderDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                lenderDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                lenderDictationStatus.classList.add('hidden');
                lenderDictationStatus.classList.remove('flex');
            };

            lenderRecognition.onend = function() {
                isLenderListening = false;
                lenderDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                lenderDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                lenderDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                lenderDictationStatus.classList.add('hidden');
                lenderDictationStatus.classList.remove('flex');
            };
        }

        // === Dictado para descripción ===
        if (dictateButton) {
            const recognition = new SpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.continuous = true;
            recognition.interimResults = true;

            let isListening = false;

            // Iniciar/detener dictado de descripción
            dictateButton.addEventListener('click', function() {
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
                        alert('No se pudo iniciar el reconocimiento de voz: ' + error.message);
                    }
                }
            });

            // Manejar resultados del reconocimiento de descripción
            recognition.onresult = function(event) {
                const results = event.results;
                const currentText = descriptionField.value;

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
                            descriptionField.value = currentText + ' ' + transcript.charAt(0).toUpperCase() + transcript.slice(1);
                        } else {
                            descriptionField.value = currentText + ' ' + transcript;
                        }
                    } else {
                        // Campo vacío, comenzar con mayúscula
                        descriptionField.value = transcript.charAt(0).toUpperCase() + transcript.slice(1);
                    }
                }
            };

            // Manejar errores y finalización
            recognition.onerror = function(event) {
                console.error('Error de reconocimiento:', event.error);
                isListening = false;
                dictateButton.innerHTML = '<i class="fas fa-microphone mr-2"></i><span>Dictar</span>';
                dictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                dictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                dictationStatus.classList.add('hidden');
                dictationStatus.classList.remove('flex');

                if (event.error === 'not-allowed') {
                    alert('No se ha concedido permiso para usar el micrófono. Verifica la configuración de tu navegador.');
                } else if (event.error !== 'no-speech') {
                    alert('Error en el reconocimiento de voz: ' + event.error);
                }
            };

            recognition.onend = function() {
                if (isListening) {
                    recognition.start();
                    console.log('Reconocimiento reiniciado automáticamente');
                }
            };
        }
    });
    </script>
</body>
</html>
