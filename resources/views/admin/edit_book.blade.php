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
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    @include('admin.header')
    @include('admin.sidebar')
    <div class="w-full p-5">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-8 text-center relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-24 after:h-1 after:bg-blue-500 dark:after:bg-blue-400">
                    Actualizar Carpeta
                </h1>

                <form action="{{url('update_book',$data->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fila 1 -->
                        <div class="space-y-1">
                            <label for="N_codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° CÓDIGO</label>
                            <input type="text" id="N_codigo" name="N_codigo" value="{{$data->N_codigo}}" readonly
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 px-4 py-3 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <small class="text-gray-500 dark:text-gray-400">El código no se puede modificar para mantener la integridad del sistema.</small>
                        </div>

                        <!-- Título con botón de micrófono -->
                        <div class="space-y-1">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">TÍTULO DE LA CARPETA</label>
                            <div class="relative">
                                <input type="text" id="title" name="title" value="{{$data->title}}"
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
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

                        <!-- Fila 2 -->
                        <div class="space-y-1">
                            <label for="ambiente" class="block text-sm font-medium text-gray-700 dark:text-gray-300">AMBIENTE</label>
                            <select id="ambiente" name="ambiente"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                                <option value="">Seleccione un ambiente</option>
                                <option value="Ambiente Sistemas" {{ $data->ambiente == 'Ambiente Sistemas' ? 'selected' : '' }}>Ambiente Sistemas</option>
                                <option value="Archivos 1" {{ $data->ambiente == 'Archivos 1' ? 'selected' : '' }}>Archivos A</option>
                                <option value="Archivos 2" {{ $data->ambiente == 'Archivos 2' ? 'selected' : '' }}>Archivos B</option>
                                <option value="Archivos 3" {{ $data->ambiente == 'Archivos 3' ? 'selected' : '' }}>Archivos C</option>
                                <option value="U Técnica" {{ $data->ambiente == 'U Técnica' ? 'selected' : '' }}>Uni.Técnica</option>
                                <option value="U Contabilidad" {{ $data->ambiente == 'U Contabilidad' ? 'selected' : '' }}>Uni.Contabilidad</option>
                                <option value="Consejo" {{ $data->ambiente == 'Consejo' ? 'selected' : '' }}>Consejo</option>
                                <option value="Otros A" {{ $data->ambiente == 'Otros A' ? 'selected' : '' }}>Otros A</option>
                                <option value="Otros B" {{ $data->ambiente == 'Otros B' ? 'selected' : '' }}>Otros B</option>
                            </select>
                            <input type="hidden" id="ambiente_nombre" name="ambiente_nombre" value="{{ $data->ambiente_nombre }}">
                        </div>

                        <div class="space-y-1">
                            <label for="estante" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ESTANTE</label>
                            <!-- Contenedor para los cuadrados de estantes -->
                            <div id="estante-grid" class="grid grid-cols-10 gap-1 mt-1 p-1 border border-gray-300 dark:border-gray-600 rounded-md max-h-[120px] overflow-y-auto bg-gray-50 dark:bg-gray-700/50 hidden">
                                <!-- Los cuadrados de estantes se generarán aquí por JS -->
                                <span class="text-gray-500 dark:text-gray-400 col-span-10 text-center text-xs italic">Seleccione un ambiente primero</span>
                            </div>
                            <!-- Elemento para mostrar la selección actual -->
                            <div id="estante-display" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200 cursor-pointer flex justify-between items-center">
                                <span id="estante-text">{{ $data->estante_numero ? 'Estante ' . $data->estante_numero : 'Seleccione un estante' }}</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                            <input type="hidden" id="estante_numero" name="estante_numero" value="{{ $data->estante_numero }}">
                        </div>

                        <div class="space-y-1">
                            <label for="bandeja" class="block text-sm font-medium text-gray-700 dark:text-gray-300">BANDEJA</label>
                            <!-- Contenedor para los cuadrados de bandejas -->
                             <div id="bandeja-grid" class="grid grid-cols-8 gap-1 mt-1 p-1 border border-gray-300 dark:border-gray-600 rounded-md max-h-[80px] overflow-y-auto bg-gray-50 dark:bg-gray-700/50 hidden">
                                <!-- Los cuadrados de bandejas se generarán aquí por JS -->
                                 <span class="text-gray-500 dark:text-gray-400 col-span-8 text-center text-xs italic">Seleccione un estante primero</span>
                            </div>
                            <!-- Elemento para mostrar la selección actual -->
                            <div id="bandeja-display" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200 cursor-pointer flex justify-between items-center {{ !$data->estante_numero ? 'opacity-50' : '' }}">
                                <span id="bandeja-text">{{ $data->bandeja_numero ? 'Bandeja ' . $data->bandeja_numero : 'Seleccione una bandeja' }}</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                            <input type="hidden" id="bandeja_numero" name="bandeja_numero" value="{{ $data->bandeja_numero }}">
                        </div>

                        <input type="hidden" id="ubicacion" name="ubicacion" value="{{ $data->ubicacion }}">

                        <div class="space-y-1">
                            <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">AÑO</label>
                            <!-- Contenedor para los cuadrados de años -->
                            <div id="year-grid" class="grid grid-cols-6 gap-0.5 mt-1 p-1 border border-gray-300 dark:border-gray-600 rounded-md max-h-[150px] overflow-y-auto bg-gray-50 dark:bg-gray-700/50 hidden">
                                <!-- Los cuadrados de años se generarán aquí por JS -->
                                <span class="text-gray-500 dark:text-gray-400 col-span-6 text-center text-xs italic">Seleccione un año</span>
                            </div>
                            <!-- Elemento para mostrar la selección actual -->
                            <div id="year-display" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200 cursor-pointer flex justify-between items-center">
                                <span id="year-text">{{ $data->year ? $data->year : 'Seleccione el año' }}</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                            <input type="hidden" id="year" name="year" value="{{ $data->year }}">
                        </div>

                        <!-- Descripción con botón de dictado -->
                        <div class="space-y-1 md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DESCRIPCIÓN</label>
                            <textarea id="description" name="description" rows="4"
                                     class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">{{$data->description}}</textarea>

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

                        <!-- Fila 3 -->
                        <div class="space-y-1">
                            <label for="tomo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CÓDIGO DE LA CARPETA FÍSICA</label>
                            <input type="text" id="tomo" name="tomo" value="{{$data->tomo}}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                        </div>

                        <div class="space-y-1">
                            <label for="N_hojas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° HOJAS TOTALES</label>
                            <input type="number" id="N_hojas" name="N_hojas" value="{{$data->N_hojas}}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                        </div>

                        <!-- Fila 4 -->
                        <div class="space-y-1">
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ESTADO DE LA CARPETA</label>
                            <select id="estado" name="estado"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                                <option value="No Prestado" {{ $data->estado == 'No Prestado' ? 'selected' : '' }}>EN AMBIENTE</option>
                                <option value="Prestado" {{ $data->estado == 'Prestado' ? 'selected' : '' }}>PRESTADO</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CLASIFICACIÓN</label>
                            <input type="text" value="{{$data->category->cat_title}}" readonly
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 px-4 py-3 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <input type="hidden" name="category" value="{{$data->category_id}}">
                            <small class="text-gray-500 dark:text-gray-400">La clasificación no se puede modificar para mantener la integridad del las categorias.</small>
                        </div>

                        <!-- Fila 5 - PDF y Imagen -->
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CARPETA PDF ACTUAL</label>
                            @if($data->pdf_file)
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <span class="text-blue-600 dark:text-blue-400">{{$data->pdf_file}}</span>
                                </div>
                            @else
                                <p class="text-yellow-500 dark:text-yellow-400 mb-2">No hay PDF adjunto</p>
                            @endif

                            <div class="space-y-1">
                                <label for="pdf_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cambiar Carpeta PDF</label>
                                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/20 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800/30">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">IMAGEN DE CARPETA ACTUAL</label>
                            <img src="/book/{{$data->book_img}}"
                                 class="w-40 h-60 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow"
                                 alt="Imagen actual">

                            <div class="space-y-1">
                                <label for="book_img" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cambiar Imagen</label>
                                <input type="file" id="book_img" name="book_img" accept="image/*"
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/20 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800/30">
                            </div>

                            <!-- Botón para activar cámara -->
                            <button type="button" id="startCamera" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-camera mr-2"></i>Usar Cámara
                            </button>

                            <!-- Contenedor de la cámara -->
                            <div id="cameraContainer" class="hidden">
                                <div class="aspect-[3/4] relative bg-black rounded-md overflow-hidden">
                                    <video id="video" class="absolute inset-0 w-full h-full object-cover" autoplay playsinline></video>
                                    <div class="absolute inset-0 border-2 border-white border-opacity-50 pointer-events-none"></div>
                                </div>
                                <canvas id="canvas" class="hidden"></canvas>
                                <div class="mt-2 flex space-x-2">
                                    <button type="button" id="captureBtn" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        <i class="fas fa-camera-retro mr-2"></i>Capturar
                                    </button>
                                    <button type="button" id="stopCamera" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <i class="fas fa-times mr-2"></i>Cancelar
                                    </button>
                                </div>
                            </div>

                            <!-- Vista previa de la imagen capturada -->
                            <div id="previewContainer" class="hidden">
                                <div class="aspect-[3/4] relative bg-gray-100 dark:bg-gray-800 rounded-md overflow-hidden">
                                    <img id="preview" class="absolute inset-0 w-full h-full object-cover" alt="Vista previa">
                                </div>
                                <button type="button" id="retakeBtn" class="mt-2 w-full px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                    <i class="fas fa-redo mr-2"></i>Volver a Capturar
                                </button>
                            </div>
                        </div>

                        <!-- Botón de envío - Ocupa ambas columnas -->
                        <div class="md:col-span-2 flex justify-center mt-6">
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-400 hover:from-blue-500 hover:to-blue-600 text-white font-medium rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 duration-300 flex items-center">
                                <i class="fas fa-save mr-2"></i> Actualizar Carpeta
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.footer')

    <!-- FontAwesome para íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ELIMINADO: const categoryInput = ...
        // ELIMINADO: const comprobantesContainer = ...
        // ELIMINADO: const comprobanteInicio = ...
        // ELIMINADO: const comprobanteFin = ...
        // ELIMINADO: const totalComprobantes = ...
        // ELIMINADO: const categoryTitle = ...

        // ELIMINADO: function esComprobante(...) { ... }

        // ELIMINADO: function calcularTotalComprobantes() { ... }

        // ELIMINADO: if (esComprobante(categoryTitle)) { ... }

        // ELIMINADO: comprobanteInicio.addEventListener(...)
        // ELIMINADO: comprobanteFin.addEventListener(...)

        // ELIMINADO: document.querySelector('form').addEventListener('submit', function(e) { if (esComprobante(...)) ... });

        // Configuración de estantes por ambiente
        const estantesPorAmbiente = {
            'Ambiente Sistemas': 20,
            'Archivos 1': 20,
            'Archivos 2': 20,
            'Archivos 3': 20,
            'U Técnica': 10,
            'U Contabilidad': 10,
            'Consejo': 10,
            'Otros A': 10,
            'Otros B': 10
        };

        // Número estándar de bandejas para todos los estantes
        const numBandejas = 8; // Actualizado a 8

        // Referencias a elementos del DOM
        const ambienteSelect = document.getElementById('ambiente');
        const ambienteNombreInput = document.getElementById('ambiente_nombre');
        // Contenedores para los grids
        const estanteGrid = document.getElementById('estante-grid');
        const bandejaGrid = document.getElementById('bandeja-grid');
        // Inputs ocultos
        const estanteNumeroInput = document.getElementById('estante_numero');
        const bandejaNumeroInput = document.getElementById('bandeja_numero');
        const ubicacionInput = document.getElementById('ubicacion');

        // Estilos para los cuadrados
        const baseSquareClasses = 'flex items-center justify-center border rounded cursor-pointer transition-colors duration-150 aspect-square';
        const estanteBandejaSquareClasses = 'text-2xs p-0.5'; // Más pequeño
        const yearSquareClasses = 'text-2xs p-0.5 text-[10px]'; // Extremadamente pequeño
        const defaultSquareClasses = 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600';
        const selectedSquareClasses = 'border-blue-500 dark:border-blue-400 bg-blue-500 dark:bg-blue-400 text-white';
        const disabledSquareClasses = 'border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 cursor-not-allowed';

        // Referencias a los elementos de visualización
        const estanteDisplay = document.getElementById('estante-display');
        const estanteText = document.getElementById('estante-text');
        const bandejaDisplay = document.getElementById('bandeja-display');
        const bandejaText = document.getElementById('bandeja-text');
        const yearGrid = document.getElementById('year-grid');
        const yearDisplay = document.getElementById('year-display');
        const yearText = document.getElementById('year-text');
        const yearInput = document.getElementById('year');
        let isEstanteGridVisible = false;
        let isBandejaGridVisible = false;
        let isYearGridVisible = false;

        // Toggle para mostrar/ocultar cuadrícula de estantes
        estanteDisplay.addEventListener('click', function() {
            if (!ambienteSelect.value) {
                alert("Primero seleccione un ambiente");
                return;
            }

            // Ocultar cualquier otra grilla visible
            hideAllGrids();

            // Toggle de la cuadrícula de estantes
            if (isEstanteGridVisible) {
                estanteGrid.classList.add('hidden');
            } else {
                estanteGrid.classList.remove('hidden');
                // Generar los cuadrados si no se han generado ya
                if (estanteGrid.children.length <= 1) { // Si solo tiene el mensaje de placeholder
                    const numEstantes = estantesPorAmbiente[ambienteSelect.value] || 10;
                    generarCuadrados(estanteGrid, numEstantes, 'estante', estanteNumeroInput.value);
                }
            }
            isEstanteGridVisible = !isEstanteGridVisible;
        });

        // Toggle para mostrar/ocultar cuadrícula de bandejas
        bandejaDisplay.addEventListener('click', function() {
            if (!estanteNumeroInput.value) {
                alert("Primero seleccione un estante");
                return;
            }

            // Ocultar cualquier otra grilla visible
            hideAllGrids();

            // Toggle de la cuadrícula de bandejas
            if (isBandejaGridVisible) {
                bandejaGrid.classList.add('hidden');
            } else {
                bandejaGrid.classList.remove('hidden');
                // Generar los cuadrados si no se han generado ya
                if (bandejaGrid.children.length <= 1) { // Si solo tiene el mensaje de placeholder
                    generarCuadrados(bandejaGrid, numBandejas, 'bandeja', bandejaNumeroInput.value);
                }
            }
            isBandejaGridVisible = !isBandejaGridVisible;
        });

        // Toggle para mostrar/ocultar cuadrícula de años
        yearDisplay.addEventListener('click', function() {
            // Ocultar cualquier otra grilla visible
            hideAllGrids();

            // Toggle de la cuadrícula de años
            if (isYearGridVisible) {
                yearGrid.classList.add('hidden');
            } else {
                yearGrid.classList.remove('hidden');
                // Generar los cuadrados si no se han generado ya
                if (yearGrid.children.length <= 1) { // Si solo tiene el mensaje de placeholder
                    generarAños();
                }
            }
            isYearGridVisible = !isYearGridVisible;
        });

        // Ocultar todas las cuadrículas
        function hideAllGrids() {
            estanteGrid.classList.add('hidden');
            isEstanteGridVisible = false;
            bandejaGrid.classList.add('hidden');
            isBandejaGridVisible = false;
            yearGrid.classList.add('hidden');
            isYearGridVisible = false;
        }

        // Función para generar cuadrados (estantes o bandejas)
        function generarCuadrados(container, total, tipo, valorSeleccionado) {
            container.innerHTML = ''; // Limpiar contenedor
            if (total === 0) {
                 container.innerHTML = `<span class="text-gray-500 dark:text-gray-400 col-span-full text-center text-xs italic">Seleccione un ${tipo === 'estante' ? 'ambiente' : 'estante'} primero</span>`;
                 return;
            }

            for (let i = 1; i <= total; i++) {
                const square = document.createElement('div');
                square.textContent = i;
                square.dataset.value = i;
                const sizeClass = tipo === 'year' ? yearSquareClasses : estanteBandejaSquareClasses;
                square.className = `${baseSquareClasses} ${sizeClass} ${i == valorSeleccionado ? selectedSquareClasses : defaultSquareClasses}`;

                square.addEventListener('click', function() {
                    // Deseleccionar otros cuadrados en el mismo grid
                    container.querySelectorAll(`.${selectedSquareClasses.split(' ')[0]}`).forEach(el => {
                        el.classList.remove(...selectedSquareClasses.split(' '));
                        el.classList.add(...defaultSquareClasses.split(' '));
                    });

                    // Seleccionar el cuadrado actual
                    this.classList.remove(...defaultSquareClasses.split(' '));
                    this.classList.add(...selectedSquareClasses.split(' '));

                    // Actualizar input oculto y lógica dependiente
                    if (tipo === 'estante') {
                        estanteNumeroInput.value = i;
                        estanteText.textContent = `Estante ${i}`;

                        // Limpiar selección de bandeja
                        bandejaNumeroInput.value = '';
                        bandejaText.textContent = 'Seleccione una bandeja';
                        bandejaDisplay.classList.remove('opacity-50');

                        // Ocultar grid
                        estanteGrid.classList.add('hidden');
                        isEstanteGridVisible = false;

                        // Preparar grid de bandejas
                        generarCuadrados(bandejaGrid, numBandejas, 'bandeja', null);
                    } else if (tipo === 'bandeja') {
                        bandejaNumeroInput.value = i;
                        bandejaText.textContent = `Bandeja ${i}`;

                        // Ocultar grid
                        bandejaGrid.classList.add('hidden');
                        isBandejaGridVisible = false;
                    }

                    actualizarUbicacion();
                });
                container.appendChild(square);
            }
        }

        // Función para generar años desde 1990 hasta el año actual
        function generarAños() {
            yearGrid.innerHTML = '';
            const currentYear = new Date().getFullYear();

            for (let year = currentYear; year >= 1990; year--) {
                const square = document.createElement('div');
                square.textContent = year;
                square.dataset.value = year;
                square.className = `${baseSquareClasses} ${yearSquareClasses} ${year == yearInput.value ? selectedSquareClasses : defaultSquareClasses}`;

                square.addEventListener('click', function() {
                    // Deseleccionar otros cuadrados
                    yearGrid.querySelectorAll(`.${selectedSquareClasses.split(' ')[0]}`).forEach(el => {
                        el.classList.remove(...selectedSquareClasses.split(' '));
                        el.classList.add(...defaultSquareClasses.split(' '));
                    });

                    // Seleccionar este cuadrado
                    this.classList.remove(...defaultSquareClasses.split(' '));
                    this.classList.add(...selectedSquareClasses.split(' '));

                    // Actualizar valor
                    yearInput.value = year;
                    yearText.textContent = year;

                    // Ocultar grid
                    yearGrid.classList.add('hidden');
                    isYearGridVisible = false;
                });

                yearGrid.appendChild(square);
            }
        }

        // Cuando cambia el ambiente seleccionado
        ambienteSelect.addEventListener('change', function() {
            ambienteNombreInput.value = this.value;

            // Limpiar selección
            estanteNumeroInput.value = '';
            bandejaNumeroInput.value = '';
            estanteText.textContent = 'Seleccione un estante';
            bandejaText.textContent = 'Seleccione una bandeja';
            bandejaDisplay.classList.add('opacity-50');

            // Ocultar grids
            hideAllGrids();

            // Preparar grid de estantes (pero no mostrarlo)
            if (this.value) {
                const numEstantes = estantesPorAmbiente[this.value] || 10;
                generarCuadrados(estanteGrid, numEstantes, 'estante', null);
                estanteDisplay.classList.remove('opacity-50');
            } else {
                estanteDisplay.classList.add('opacity-50');
                bandejaDisplay.classList.add('opacity-50');
            }

            actualizarUbicacion();
        });

        // Función para actualizar el campo de ubicación completa
        function actualizarUbicacion() {
            const ambiente = ambienteSelect.value;
            const estanteNum = estanteNumeroInput.value;
            const bandejaNum = bandejaNumeroInput.value;

            // Construir texto de ubicación con formato correcto
            let ubicacionParts = [];
            if (ambiente) ubicacionParts.push(ambiente);
            if (estanteNum) ubicacionParts.push(`Estante ${estanteNum}`);
            if (bandejaNum) ubicacionParts.push(`Bandeja ${bandejaNum}`);

            ubicacionInput.value = ubicacionParts.join(', ');
        }

        // Inicializar los elementos en la carga
        if (ambienteSelect.value) {
            // Si hay un ambiente seleccionado al cargar la página
            const numEstantesInicial = estantesPorAmbiente[ambienteSelect.value] || 10;

            // Generamos los cuadrados pero no los mostramos (hidden)
            generarCuadrados(estanteGrid, numEstantesInicial, 'estante', estanteNumeroInput.value);

            if (estanteNumeroInput.value) {
                // Si hay estante, preparar bandejas
                generarCuadrados(bandejaGrid, numBandejas, 'bandeja', bandejaNumeroInput.value);
                bandejaDisplay.classList.remove('opacity-50');
            } else {
                bandejaDisplay.classList.add('opacity-50');
            }
        } else {
            estanteDisplay.classList.add('opacity-50');
            bandejaDisplay.classList.add('opacity-50');
        }

        // Inicializar el año si ya tiene valor
        if (yearInput.value) {
            yearText.textContent = yearInput.value;
        }

        // Variables para la cámara
        let stream = null;
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const startButton = document.getElementById('startCamera');
        const stopButton = document.getElementById('stopCamera');
        const captureButton = document.getElementById('captureBtn');
        const retakeButton = document.getElementById('retakeBtn');
        const cameraContainer = document.getElementById('cameraContainer');
        const previewContainer = document.getElementById('previewContainer');
        const preview = document.getElementById('preview');
        const bookImgInput = document.getElementById('book_img');

        // Iniciar cámara
        startButton.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                cameraContainer.classList.remove('hidden');
                startButton.classList.add('hidden');
            } catch (err) {
                console.error('Error al acceder a la cámara:', err);
                alert('No se pudo acceder a la cámara. Por favor, verifica los permisos.');
            }
        });

        // Detener cámara
        stopButton.addEventListener('click', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            cameraContainer.classList.add('hidden');
            startButton.classList.remove('hidden');
        });

        // Capturar imagen
        captureButton.addEventListener('click', () => {
            const aspectRatio = 3/4;
            const videoWidth = video.videoWidth;
            const videoHeight = video.videoHeight;

            let cropWidth, cropHeight;
            if (videoWidth / videoHeight > aspectRatio) {
                cropHeight = videoHeight;
                cropWidth = videoHeight * aspectRatio;
            } else {
                cropWidth = videoWidth;
                cropHeight = videoWidth / aspectRatio;
            }

            const x = (videoWidth - cropWidth) / 2;
            const y = (videoHeight - cropHeight) / 2;

            canvas.width = cropWidth;
            canvas.height = cropHeight;

            canvas.getContext('2d').drawImage(
                video,
                x, y, cropWidth, cropHeight,
                0, 0, cropWidth, cropHeight
            );

            canvas.toBlob(blob => {
                const file = new File([blob], "captured_image.jpg", { type: "image/jpeg" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                bookImgInput.files = dataTransfer.files;

                preview.src = canvas.toDataURL('image/jpeg');
                previewContainer.classList.remove('hidden');
                cameraContainer.classList.add('hidden');
                startButton.classList.remove('hidden');

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
            }, 'image/jpeg', 0.9);
        });

        // Volver a capturar
        retakeButton.addEventListener('click', () => {
            previewContainer.classList.add('hidden');
            startButton.click();
        });

        // Limpiar al cerrar la página
        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        });

        // Variables para dictado
        const dictateButton = document.getElementById('dictateButton');
        const dictationStatus = document.getElementById('dictationStatus');
        const descriptionField = document.getElementById('description');
        const titleDictateButton = document.getElementById('titleDictateButton');
        const titleDictationStatus = document.getElementById('titleDictationStatus');
        const titleField = document.getElementById('title');

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
            return;
        }

        // === Dictado para título ===
        if (titleDictateButton) {
            const titleRecognition = new SpeechRecognition();
            titleRecognition.lang = 'es-ES';
            titleRecognition.continuous = false; // Una sola frase para el título
            titleRecognition.interimResults = false;

            let isTitleListening = false;
            let titleConsecutiveErrors = 0;
            const TITLE_MAX_CONSECUTIVE_ERRORS = 3;
            let titleBackoffTime = 1000; // Tiempo inicial de espera: 1 segundo
            let titleRecognitionAttempts = 0;
            const TITLE_MAX_ATTEMPTS = 5; // Máximo número de intentos para el título

            // Iniciar/detener dictado del título
            titleDictateButton.addEventListener('click', function() {
                if (isTitleListening) {
                    // Detener reconocimiento
                    try {
                        titleRecognition.stop();
                        titleRecognitionAttempts = 0; // Reiniciar contador de intentos
                    } catch (error) {
                        console.error('Error al detener reconocimiento de título:', error);
                    }
                    isTitleListening = false;
                    titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                    titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                    titleDictationStatus.classList.add('hidden');
                    titleDictationStatus.classList.remove('flex');
                    titleDictationStatus.querySelector('span').textContent = 'Escuchando...';
                    console.log('Dictado de título detenido');
                } else {
                    // Reiniciar contadores
                    titleConsecutiveErrors = 0;
                    titleBackoffTime = 1000;
                    titleRecognitionAttempts = 0;

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

                    // Reiniciar contadores de error al tener un resultado exitoso
                    titleConsecutiveErrors = 0;
                    titleBackoffTime = 1000;
                }
            };

            // Manejar errores y finalización
            titleRecognition.onerror = function(event) {
                console.error('Error de reconocimiento de título:', event.error);

                // Incrementar contador de errores consecutivos
                titleConsecutiveErrors++;
                titleRecognitionAttempts++;

                // Mostrar tipo de error al usuario
                let errorMsg = '';
                if (event.error === 'network') {
                    errorMsg = 'Error de red. Comprobando conexión...';
                } else if (event.error === 'no-speech') {
                    errorMsg = 'No se detectó voz. Intente hablar más fuerte.';
                } else if (event.error === 'aborted') {
                    errorMsg = 'Reconocimiento cancelado.';
                } else {
                    errorMsg = `Error: ${event.error}`;
                }

                // Actualizar estado de dictado
                titleDictationStatus.querySelector('span').textContent = errorMsg;
                titleDictationStatus.querySelector('i').classList.remove('fa-circle-notch', 'fa-spin');
                titleDictationStatus.querySelector('i').classList.add('fa-exclamation-circle');
                titleDictationStatus.classList.remove('text-green-500');
                titleDictationStatus.classList.add('text-yellow-500');

                // Verificar si debemos detener completamente
                if (titleConsecutiveErrors >= TITLE_MAX_CONSECUTIVE_ERRORS ||
                    titleRecognitionAttempts >= TITLE_MAX_ATTEMPTS) {
                    console.warn('Demasiados errores o intentos para el título. Deteniendo reconocimiento.');

                    isTitleListening = false;
                    titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                    titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');

                    // Mostrar mensaje final de error
                    setTimeout(() => {
                        alert('Se ha alcanzado el límite de errores. Por favor, inténtelo de nuevo más tarde.');
                        titleDictationStatus.classList.add('hidden');
                        titleDictationStatus.classList.remove('flex');
                        // Restaurar para el próximo uso
                        titleDictationStatus.querySelector('i').classList.add('fa-circle-notch', 'fa-spin');
                        titleDictationStatus.querySelector('i').classList.remove('fa-exclamation-circle');
                        titleDictationStatus.classList.add('text-green-500');
                        titleDictationStatus.classList.remove('text-yellow-500');
                        titleDictationStatus.querySelector('span').textContent = 'Escuchando...';
                    }, 1500);
                }
            };

            titleRecognition.onend = function() {
                // Si aún estamos en modo de escucha y no hemos excedido los límites
                if (isTitleListening &&
                    titleConsecutiveErrors < TITLE_MAX_CONSECUTIVE_ERRORS &&
                    titleRecognitionAttempts < TITLE_MAX_ATTEMPTS) {

                    titleRecognitionAttempts++;

                    // Intentar reiniciar después de un tiempo
                    setTimeout(() => {
                        if (isTitleListening) {
                            try {
                                titleRecognition.start();
                                console.log(`Reiniciando reconocimiento de título (intento ${titleRecognitionAttempts})`);
                                // Restaurar indicador visual
                                titleDictationStatus.querySelector('span').textContent = 'Escuchando...';
                                titleDictationStatus.querySelector('i').classList.add('fa-circle-notch', 'fa-spin');
                                titleDictationStatus.querySelector('i').classList.remove('fa-exclamation-circle');
                                titleDictationStatus.classList.add('text-green-500');
                                titleDictationStatus.classList.remove('text-yellow-500');
                            } catch (error) {
                                console.error('Error al reiniciar reconocimiento de título:', error);
                                isTitleListening = false;
                                titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                                titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                                titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                                titleDictationStatus.classList.add('hidden');
                            }
                        }
                    }, titleBackoffTime);

                    // Aumentar el tiempo de espera para el próximo intento
                    titleBackoffTime = Math.min(titleBackoffTime * 1.5, 5000);
                } else {
                    // Finalizar el reconocimiento
                    isTitleListening = false;
                    titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                    titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');

                    // Ocultar el estado después de un breve retraso
                    if (titleConsecutiveErrors === 0) {
                        titleDictationStatus.classList.add('hidden');
                        titleDictationStatus.classList.remove('flex');
                    }
                }
            };
        }

        // === Dictado para descripción ===
        if (dictateButton) {
            const recognition = new SpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.continuous = true;
            recognition.interimResults = true;

            let isListening = false;
            let consecutiveErrors = 0;
            const MAX_CONSECUTIVE_ERRORS = 3;
            let restartTimeout = null;
            let backoffTime = 1000; // Tiempo inicial de espera: 1 segundo
            let recognitionAttempts = 0;
            const MAX_RECOGNITION_ATTEMPTS = 8; // Máximo número de intentos totales

            // Iniciar/detener dictado de descripción
            dictateButton.addEventListener('click', function() {
                if (isListening) {
                    // Detener reconocimiento
                    try {
                        recognition.stop();
                        recognitionAttempts = 0; // Reiniciar contador de intentos
                    } catch (error) {
                        console.error('Error al detener reconocimiento:', error);
                    }

                    // Limpiar cualquier timeout pendiente
                    if (restartTimeout) {
                        clearTimeout(restartTimeout);
                        restartTimeout = null;
                    }

                    isListening = false;
                    dictateButton.innerHTML = '<i class="fas fa-microphone mr-2"></i><span>Dictar</span>';
                    dictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    dictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                    dictationStatus.classList.add('hidden');
                    dictationStatus.classList.remove('flex');
                    dictationStatus.querySelector('span').textContent = 'Escuchando...';
                    console.log('Dictado detenido');
                } else {
                    // Reiniciar contadores
                    consecutiveErrors = 0;
                    backoffTime = 1000;
                    recognitionAttempts = 0;

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

                    // Reiniciar contadores de error al tener éxito
                    consecutiveErrors = 0;
                    backoffTime = 1000;

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

                // Incrementar contador de errores consecutivos
                consecutiveErrors++;
                recognitionAttempts++;

                // Mostrar tipo de error al usuario
                let errorMsg = '';
                if (event.error === 'not-allowed') {
                    errorMsg = 'Permiso de micrófono denegado';
                    isListening = false; // Detener inmediatamente
                } else if (event.error === 'network') {
                    errorMsg = 'Error de red. Verificando conexión...';
                } else if (event.error === 'aborted') {
                    errorMsg = 'Reconocimiento cancelado';
                } else if (event.error === 'no-speech') {
                    errorMsg = 'No se detectó voz. Hable más fuerte.';
                } else {
                    errorMsg = `Error: ${event.error}`;
                }

                // Actualizar estado de dictado
                dictationStatus.querySelector('span').textContent = errorMsg;
                dictationStatus.querySelector('i').classList.remove('fa-circle-notch', 'fa-spin');
                dictationStatus.querySelector('i').classList.add('fa-exclamation-circle');
                dictationStatus.classList.remove('text-green-500');
                dictationStatus.classList.add('text-yellow-500');

                // Detener reconocimiento si hay demasiados errores o intentos
                if (consecutiveErrors >= MAX_CONSECUTIVE_ERRORS ||
                    recognitionAttempts >= MAX_RECOGNITION_ATTEMPTS ||
                    !isListening) {

                    isListening = false;
                    dictateButton.innerHTML = '<i class="fas fa-microphone mr-2"></i><span>Dictar</span>';
                    dictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    dictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');

                    // Limpiar cualquier timeout pendiente
                    if (restartTimeout) {
                        clearTimeout(restartTimeout);
                        restartTimeout = null;
                    }

                    // Mostrar mensaje final después de breve retraso
                    setTimeout(() => {
                        if (consecutiveErrors >= MAX_CONSECUTIVE_ERRORS) {
                            alert('Se ha alcanzado el límite de errores. Por favor, inténtelo de nuevo más tarde.');
                        } else if (recognitionAttempts >= MAX_RECOGNITION_ATTEMPTS) {
                            alert('Se ha alcanzado el límite de intentos. Por favor, inténtelo de nuevo más tarde.');
                        }

                        dictationStatus.classList.add('hidden');
                        dictationStatus.classList.remove('flex');
                        // Restaurar para el próximo uso
                        dictationStatus.querySelector('i').classList.add('fa-circle-notch', 'fa-spin');
                        dictationStatus.querySelector('i').classList.remove('fa-exclamation-circle');
                        dictationStatus.classList.add('text-green-500');
                        dictationStatus.classList.remove('text-yellow-500');
                        dictationStatus.querySelector('span').textContent = 'Escuchando...';
                    }, 1500);
                }
            };

            recognition.onend = function() {
                // Solo intentar reiniciar si todavía estamos en modo de escucha
                // y no hemos alcanzado el límite de errores consecutivos o intentos totales
                if (isListening &&
                    consecutiveErrors < MAX_CONSECUTIVE_ERRORS &&
                    recognitionAttempts < MAX_RECOGNITION_ATTEMPTS) {

                    console.log('Reconocimiento finalizado, intentando reiniciar...');
                    recognitionAttempts++;

                    // Usar un tiempo de espera progresivo para evitar demasiadas solicitudes
                    backoffTime = Math.min(backoffTime * 1.5, 8000); // Incremento exponencial, máximo 8 segundos
                    console.log(`Esperando ${backoffTime}ms antes de reintentar... (intento ${recognitionAttempts})`);

                    // Limpiar cualquier timeout pendiente
                    if (restartTimeout) {
                        clearTimeout(restartTimeout);
                    }

                    // Establecer un nuevo timeout con retraso progresivo
                    restartTimeout = setTimeout(() => {
                        try {
                            if (isListening) { // Verificar que aún queremos escuchar
                                console.log(`Reiniciando reconocimiento después de ${backoffTime}ms`);
                                recognition.start();

                                // Restaurar indicador visual
                                dictationStatus.querySelector('span').textContent = 'Escuchando...';
                                dictationStatus.querySelector('i').classList.add('fa-circle-notch', 'fa-spin');
                                dictationStatus.querySelector('i').classList.remove('fa-exclamation-circle');
                                dictationStatus.classList.add('text-green-500');
                                dictationStatus.classList.remove('text-yellow-500');
                            }
                        } catch (error) {
                            console.error('Error al reiniciar el reconocimiento:', error);
                            isListening = false;
                            dictateButton.innerHTML = '<i class="fas fa-microphone mr-2"></i><span>Dictar</span>';
                            dictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                            dictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                            dictationStatus.classList.add('hidden');
                            dictationStatus.classList.remove('flex');

                            // Notificar al usuario
                            alert('No se pudo reiniciar el reconocimiento de voz. Por favor, inténtelo de nuevo más tarde.');
                        }
                    }, backoffTime);
                } else {
                    // Finalizar el reconocimiento si hemos excedido los límites
                    isListening = false;
                    dictateButton.innerHTML = '<i class="fas fa-microphone mr-2"></i><span>Dictar</span>';
                    dictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    dictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');

                    // Ocultar el estado si no hubo errores
                    if (consecutiveErrors === 0) {
                        dictationStatus.classList.add('hidden');
                        dictationStatus.classList.remove('flex');
                    }

                    // Limpiar timeout
                    if (restartTimeout) {
                        clearTimeout(restartTimeout);
                        restartTimeout = null;
                    }
                }
            };
        }
    });
    </script>
</body>
</html>
