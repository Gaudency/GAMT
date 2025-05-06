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
    <div class="flex-16 min-h-screen">
        <div class="w-full p-5">
            <div class="container mx-auto max-w-7xl">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg backdrop-blur-lg border border-gray-200 dark:border-gray-700">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-8 text-center relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-24 after:h-1 after:bg-blue-500 dark:after:bg-blue-400">
                        Añadir Nueva Carpeta
                    </h2>

                    <form action="{{ url('store_book') }}" method="POST" enctype="multipart/form-data" id="bookForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Columna izquierda -->
                            <div class="space-y-5">
                                <!-- N° Código -->
                                <div class="relative">
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <input type="text" id="N_codigo_display" readonly
                                                class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 px-4 py-3 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                                                value="Ejemplo: 1000-AB">
                                            <label for="N_codigo_display"
                                                class="absolute left-2 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white dark:bg-gray-700 px-2 text-gray-600 dark:text-gray-400 duration-300">
                                                N° Código (Autogenerado)
                                            </label>
                                        </div>
                                    </div>
                                    <small class="text-gray-500 dark:text-gray-400 ml-3 mt-1 block">Código generado automáticamente a partir de 1000 acompañado de dos letras aleatorias:(ejemplo.2001-AB)</small>
                                     <small class="text-gray-500 dark:text-gray-400 ml-3 mt-1 block">Copiar el codigo e imprimirla y pegarlo en la carpeta fisica una ves creado la carpera digital</small>
                                </div>

                                <!-- Título -->
                                <div class="relative">
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-heading"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <div class="flex">
                                                <input type="text" id="title" name="title"
                                                    class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                                    placeholder="ponga el nombre completo de la carpeta a digitalizar"
                                                    style="text-transform: uppercase;"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                <button type="button" id="titleDictateButton"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-purple-500 hover:bg-purple-600 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm transition-colors">
                                                    <i class="fas fa-microphone"></i>
                                                </button>
                                            </div>
                                            <label for="title"
                                                class="absolute left-2 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white dark:bg-gray-700 px-2 text-gray-600 dark:text-gray-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-blue-500 dark:peer-focus:text-blue-400">
                                                Título
                                            </label>
                                            <div id="titleDictationStatus" class="hidden text-xs text-green-500 mt-1 flex items-center">
                                                <i class="fas fa-circle-notch fa-spin mr-1"></i>
                                                <span>Escuchando...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ubicación - Sistema de ubicación específica -->
                                <div class="relative mb-5">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ubicación específica</label>

                                    <!-- Ambiente -->
                                    <div class="flex mb-3">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <select id="ambiente" name="ambiente" class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                                                <option value="">Seleccione un ambiente</option>
                                                <option value="Ambiente Sistemas">Ambiente Sistemas</option>
                                                <option value="Archivos 1">Archivos A</option>
                                                <option value="Archivos 2">Archivos B</option>
                                                <option value="Archivos 3">Archivos C</option>
                                                <option value="U Técnica">Uni.Técnica</option>
                                                <option value="U Contabilidad">Uni.Contabilidad</option>
                                                <option value="Consejo">Consejo</option>
                                                <option value="Otros A">Otros A</option>
                                                <option value="Otros B">Otros B</option>
                                            </select>
                                            <input type="hidden" id="ambiente_nombre" name="ambiente_nombre">
                                        </div>
                                    </div>

                                    <!-- Estante -->
                                    <div class="flex mb-3">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-archive"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <!-- Grid de estantes -->
                                            <div id="estante-grid" class="grid grid-cols-10 gap-1 mt-1 p-1 border border-gray-300 dark:border-gray-600 rounded-md max-h-[120px] overflow-y-auto bg-gray-50 dark:bg-gray-700/50 hidden">
                                                <!-- Los cuadrados de estantes se generarán aquí por JS -->
                                                <span class="text-gray-500 dark:text-gray-400 col-span-10 text-center text-xs italic">Seleccione un ambiente primero</span>
                                            </div>
                                            <!-- Elemento para mostrar la selección actual -->
                                            <div id="estante-display" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200 cursor-pointer flex justify-between items-center opacity-50">
                                                <span id="estante-text">Seleccione un estante</span>
                                                <i class="fas fa-chevron-down text-gray-400"></i>
                                            </div>
                                            <input type="hidden" id="estante_numero" name="estante_numero">
                                        </div>
                                    </div>

                                    <!-- Bandeja -->
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <!-- Grid de bandejas -->
                                            <div id="bandeja-grid" class="grid grid-cols-8 gap-1 mt-1 p-1 border border-gray-300 dark:border-gray-600 rounded-md max-h-[80px] overflow-y-auto bg-gray-50 dark:bg-gray-700/50 hidden">
                                                <!-- Los cuadrados de bandejas se generarán aquí por JS -->
                                                <span class="text-gray-500 dark:text-gray-400 col-span-8 text-center text-xs italic">Seleccione un estante primero</span>
                                            </div>
                                            <!-- Elemento para mostrar la selección actual -->
                                            <div id="bandeja-display" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200 cursor-pointer flex justify-between items-center opacity-50">
                                                <span id="bandeja-text">Seleccione una bandeja</span>
                                                <i class="fas fa-chevron-down text-gray-400"></i>
                                            </div>
                                            <input type="hidden" id="bandeja_numero" name="bandeja_numero">
                                        </div>
                                    </div>

                                    <!-- Campo oculto para la ubicación completa -->
                                    <input type="hidden" id="ubicacion" name="ubicacion">
                                </div>
                            </div>
                            <!-- Columna derecha -->
                            <div class="space-y-5">
                                <!-- Código Carpeta Física -->
                                <div class="relative">
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-book"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <input type="text" id="tomo" name="tomo"
                                                class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                                placeholder="el codigo que posee la carpeta fisica ejm Tomo 5 o el cuse- etc ">
                                            <label for="tomo"
                                                class="absolute left-2 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white dark:bg-gray-700 px-2 text-gray-600 dark:text-gray-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-blue-500 dark:peer-focus:text-blue-400">
                                                Código de la Carpeta Física
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- N° Hojas -->
                                <div class="relative">
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-file-alt"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <input type="number" id="N_hojas" name="N_hojas"
                                                class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                                placeholder="hojas totales foleadas">
                                            <label for="N_hojas"
                                                class="absolute left-2 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white dark:bg-gray-700 px-2 text-gray-600 dark:text-gray-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-blue-500 dark:peer-focus:text-blue-400">
                                                N° Hojas
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Categoría -->
                                <div class="mb-3">
                                    <label for="category" class="form-label">Categoría</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Seleccione una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}"
                                                    data-tipo="{{ $category['tipo'] }}">
                                                {{ $category['title'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="categoryTypeIndicator" class="mt-2 p-2 rounded" style="display:none;">
                                        <span id="categoryTypeText"></span>
                                    </div>
                                </div>
                                  <!-- Año -->
                                  <div class="relative">
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <!-- Grid de años -->
                                            <div id="year-grid" class="grid grid-cols-6 gap-0.5 mt-1 p-1 border border-gray-300 dark:border-gray-600 rounded-md max-h-[150px] overflow-y-auto bg-gray-50 dark:bg-gray-700/50 hidden">
                                                <!-- Los cuadrados de años se generarán aquí por JS -->
                                                <span class="text-gray-500 dark:text-gray-400 col-span-6 text-center text-xs italic">Seleccione un año</span>
                                            </div>
                                            <!-- Elemento para mostrar la selección actual -->
                                            <div id="year-display" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200 cursor-pointer flex justify-between items-center">
                                                <span id="year-text">Seleccione el año</span>
                                                <i class="fas fa-chevron-down text-gray-400"></i>
                                            </div>
                                            <input type="hidden" id="year" name="year" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- Estado -->
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    <select class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                        name="estado" id="estado" required>
                                        <option value="No Prestado">EN AMBIENTE</option>
                                        <option value="Prestado">PRESTADO</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Descripción - Ocupa ambas columnas -->
                            <div class="col-span-1 md:col-span-2">
                                <div class="flex flex-col">
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-align-left"></i>
                                        </span>
                                        <div class="relative flex-1">
                                            <textarea id="description" name="description" rows="4"
                                                class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                                placeholder="aqui puedes describir las datos adicionales  como estado  o si faltan hojas o si se movera de lugar etc."></textarea>
                                            <label for="description"
                                                class="absolute left-2 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white dark:bg-gray-700 px-2 text-gray-600 dark:text-gray-400 duration-300 peer-placeholder-shown:top-6 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-blue-500 dark:peer-focus:text-blue-400">
                                                Descripción
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Botón de dictado por voz -->
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
                            </div>

                            <!-- Imagen del libro -->
                            <div class="col-span-1 md:col-span-1">
                                <div class="flex flex-col space-y-4">
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                            <i class="fas fa-image"></i>
                                        </span>
                                        <div class="relative w-full">
                                            <input type="file" id="book_img" name="book_img" accept="image/*"
                                                class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/20 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800/30">
                                        </div>
                                    </div>

                                    <!-- Botón para activar cámara -->
                                    <button type="button" id="startCamera" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <i class="fas fa-camera mr-2"></i>Usar Cámara
                                    </button>

                                    <!-- Contenedor de la cámara (inicialmente oculto) -->
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
                            </div>

                            <!-- Archivo PDF -->
                            <div class="col-span-1 md:col-span-1">
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                        <i class="fas fa-file-pdf"></i>
                                    </span>
                                    <div class="relative w-full">
                                        <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf" required
                                            class="w-full rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/20 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800/30">
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Comprobantes (inicialmente oculta) -->
                            <div id="comprobantesSection" class="mt-6 hidden">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-4">
                                        <i class="fas fa-receipt mr-2"></i> Detalles de Comprobantes
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="relative">
                                            <input type="number" id="comprobante_inicio" name="comprobante_inicio" min="1"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                                            <label for="comprobante_inicio"
                                                class="absolute left-2 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white dark:bg-gray-700 px-2 text-gray-600 dark:text-gray-400 duration-300">
                                                N° Comprobante Inicial
                                            </label>
                                        </div>
                                        <div class="relative">
                                            <input type="number" id="comprobante_fin" name="comprobante_fin" min="1"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                                            <label for="comprobante_fin"
                                                class="absolute left-2 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white dark:bg-gray-700 px-2 text-gray-600 dark:text-gray-400 duration-300">
                                                N° Comprobante Final
                                            </label>
                                        </div>
                                    </div>
                                    <div id="comprobantesTotal" class="mt-4 text-sm text-gray-600 dark:text-gray-400"></div>
                                </div>
                            </div>

                            <!-- Botón de envío -->
                            <div class="col-span-1 md:col-span-2 flex justify-center mt-6">
                                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-400 hover:from-red-500 hover:to-red-600 text-white font-medium rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 duration-300 flex items-center">
                                    <i class="fas fa-save mr-2"></i>Guardar Carpeta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')

    <!-- FontAwesome para íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const comprobantesSection = document.getElementById('comprobantesSection');
        const comprobanteInicio = document.getElementById('comprobante_inicio');
        const comprobanteFin = document.getElementById('comprobante_fin');
        const comprobantesTotal = document.getElementById('comprobantesTotal');
        const ambienteSelect = document.getElementById('ambiente');
        const ambienteNombreInput = document.getElementById('ambiente_nombre');

        // Elementos para los sistemas de interfaz visual
        const estanteDisplay = document.getElementById('estante-display');
        const estanteText = document.getElementById('estante-text');
        const estanteGrid = document.getElementById('estante-grid');
        const estanteNumeroInput = document.getElementById('estante_numero');

        const bandejaDisplay = document.getElementById('bandeja-display');
        const bandejaText = document.getElementById('bandeja-text');
        const bandejaGrid = document.getElementById('bandeja-grid');
        const bandejaNumeroInput = document.getElementById('bandeja_numero');

        const yearDisplay = document.getElementById('year-display');
        const yearText = document.getElementById('year-text');
        const yearGrid = document.getElementById('year-grid');
        const yearInput = document.getElementById('year');

        const ubicacionInput = document.getElementById('ubicacion');

        let isEstanteGridVisible = false;
        let isBandejaGridVisible = false;
        let isYearGridVisible = false;

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

        // Número de bandejas para todos los estantes
        const numBandejas = 8;

        // Estilos para los cuadrados
        const baseSquareClasses = 'flex items-center justify-center border rounded cursor-pointer transition-colors duration-150 aspect-square';
        const estanteBandejaSquareClasses = 'text-2xs p-0.5'; // Más pequeño
        const yearSquareClasses = 'text-2xs p-0.5 text-[10px]'; // Extremadamente pequeño
        const defaultSquareClasses = 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600';
        const selectedSquareClasses = 'border-blue-500 dark:border-blue-400 bg-blue-500 dark:bg-blue-400 text-white';
        const disabledSquareClasses = 'border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 cursor-not-allowed';

        // Ocultar todas las cuadrículas
        function hideAllGrids() {
            estanteGrid.classList.add('hidden');
            isEstanteGridVisible = false;
            bandejaGrid.classList.add('hidden');
            isBandejaGridVisible = false;
            yearGrid.classList.add('hidden');
            isYearGridVisible = false;
        }

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

        function updateComprobantesSection() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const isComprobante = selectedOption && selectedOption.dataset.tipo === 'comprobante';

            comprobantesSection.classList.toggle('hidden', !isComprobante);
            comprobanteInicio.required = isComprobante;
            comprobanteFin.required = isComprobante;
        }

        function updateComprobantesTotal() {
            const inicio = parseInt(comprobanteInicio.value) || 0;
            const fin = parseInt(comprobanteFin.value) || 0;
            const total = fin >= inicio ? fin - inicio + 1 : 0;

            comprobantesTotal.textContent = total > 0
                ? `Se crearán ${total} comprobantes en total`
                : '';
        }

        categorySelect.addEventListener('change', updateComprobantesSection);
        comprobanteInicio.addEventListener('input', updateComprobantesTotal);
        comprobanteFin.addEventListener('input', updateComprobantesTotal);

        // Validación del formulario
        document.getElementById('bookForm').addEventListener('submit', function(e) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const isComprobante = selectedOption && selectedOption.dataset.tipo === 'comprobante';

            if (isComprobante) {
                const inicio = parseInt(comprobanteInicio.value);
                const fin = parseInt(comprobanteFin.value);

                if (!inicio || !fin || fin < inicio) {
                    e.preventDefault();
                    alert('Por favor, ingrese un rango válido de comprobantes');
                    return;
                }
            }
        });

        // SISTEMA DE UBICACIÓN MEJORADO
        // Cuando cambia el ambiente seleccionado
        ambienteSelect.addEventListener('change', function() {
            // Guardar el nombre del ambiente
            ambienteNombreInput.value = this.value;

            // Limpiar selecciones
            estanteNumeroInput.value = '';
            bandejaNumeroInput.value = '';
            estanteText.textContent = 'Seleccione un estante';
            bandejaText.textContent = 'Seleccione una bandeja';

            // Ocultar grids
            hideAllGrids();

            if (this.value) {
                // Habilitar selección de estante
                estanteDisplay.classList.remove('opacity-50');
                // Pero desactivar bandeja hasta que se seleccione estante
                bandejaDisplay.classList.add('opacity-50');

                // Preparar grid de estantes para este ambiente
                const numEstantes = estantesPorAmbiente[this.value] || 10;
                generarCuadrados(estanteGrid, numEstantes, 'estante', null);
            } else {
                // Deshabilitar selectores si no hay ambiente seleccionado
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

        // Capturar imagen con proporción vertical
        captureButton.addEventListener('click', () => {
            const aspectRatio = 3/4; // Proporción vertical
            const videoWidth = video.videoWidth;
            const videoHeight = video.videoHeight;

            // Calcular dimensiones para mantener la proporción 3:4
            let cropWidth, cropHeight;
            if (videoWidth / videoHeight > aspectRatio) {
                // Si el video es más ancho que la proporción deseada
                cropHeight = videoHeight;
                cropWidth = videoHeight * aspectRatio;
            } else {
                // Si el video es más alto que la proporción deseada
                cropWidth = videoWidth;
                cropHeight = videoWidth / aspectRatio;
            }

            // Calcular posición para centrar el recorte
            const x = (videoWidth - cropWidth) / 2;
            const y = (videoHeight - cropHeight) / 2;

            // Configurar canvas con las dimensiones deseadas
            canvas.width = cropWidth;
            canvas.height = cropHeight;

            // Dibujar la porción recortada del video
            canvas.getContext('2d').drawImage(
                video,
                x, y, cropWidth, cropHeight,  // Área de recorte
                0, 0, cropWidth, cropHeight   // Área de destino
            );

            // Convertir canvas a blob y crear archivo
            canvas.toBlob(blob => {
                const file = new File([blob], "captured_image.jpg", { type: "image/jpeg" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                bookImgInput.files = dataTransfer.files;

                // Mostrar vista previa
                preview.src = canvas.toDataURL('image/jpeg');
                previewContainer.classList.remove('hidden');
                cameraContainer.classList.add('hidden');
                startButton.classList.remove('hidden');

                // Detener la cámara después de capturar
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

        // Función para generar código de ejemplo
        function generateExampleCode() {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const number = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;
            const letter1 = letters[Math.floor(Math.random() * letters.length)];
            const letter2 = letters[Math.floor(Math.random() * letters.length)];
            return `${number}-${letter1}${letter2}`;
        }

        // Actualizar el ejemplo cada 3 segundos
        const codigoDisplay = document.getElementById('N_codigo_display');
        setInterval(() => {
            codigoDisplay.value = `Ejemplo: ${generateExampleCode()}`;
        }, 3000);

        // JavaScript para dictado de voz
        const dictateButton = document.getElementById('dictateButton');
        const dictationStatus = document.getElementById('dictationStatus');
        const descriptionField = document.getElementById('description');

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

        // Agregar dictado para el campo de título
        const titleDictateButton = document.getElementById('titleDictateButton');
        const titleDictationStatus = document.getElementById('titleDictationStatus');
        const titleField = document.getElementById('title');

        if (titleDictateButton) {
            // Verificar soporte para SpeechRecognition
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                console.error('Tu navegador no soporta el reconocimiento de voz.');
                titleDictateButton.disabled = true;
                titleDictateButton.classList.add('bg-gray-400');
                titleDictateButton.classList.remove('bg-purple-500', 'hover:bg-purple-600');
                titleDictateButton.title = 'Reconocimiento de voz no disponible en este navegador';
            } else {
                // Crear instancia de reconocimiento de voz para título
                const titleRecognition = new SpeechRecognition();
                titleRecognition.lang = 'es-ES';
                titleRecognition.continuous = false; // Una sola frase para el título
                titleRecognition.interimResults = false;

                let isTitleListening = false;

                // Función para iniciar/detener dictado del título
                function toggleTitleDictation() {
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
                        }
                    }
                }

                // Asignar evento al botón
                titleDictateButton.addEventListener('click', toggleTitleDictation);

                // Manejar resultados del reconocimiento
                titleRecognition.onresult = function(event) {
                    const results = event.results;

                    // Obtener la transcripción
                    if (results.length > 0 && results[0].isFinal) {
                        let transcript = results[0][0].transcript;
                        console.log('Título reconocido:', transcript);

                        // Convertir a mayúsculas (como requiere el campo)
                        transcript = transcript.toUpperCase();

                        // Establecer en el campo
                        titleField.value = transcript;
                    }
                };

                // Manejar errores
                titleRecognition.onerror = function(event) {
                    console.error('Error de reconocimiento de título:', event.error);
                    isTitleListening = false;
                    titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                    titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                    titleDictationStatus.classList.add('hidden');
                    titleDictationStatus.classList.remove('flex');
                };

                // Cuando el reconocimiento termina
                titleRecognition.onend = function() {
                    isTitleListening = false;
                    titleDictateButton.innerHTML = '<i class="fas fa-microphone"></i>';
                    titleDictateButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    titleDictateButton.classList.add('bg-purple-500', 'hover:bg-purple-600');
                    titleDictationStatus.classList.add('hidden');
                    titleDictationStatus.classList.remove('flex');
                };
            }
        }
    });
    </script>
</body>
</html>
