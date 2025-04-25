@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('bookCreated'))
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="bg-green-500 dark:bg-green-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">{{ session('bookCreated')['title'] }}</h2>
            </div>

            <!-- Contenido -->
            <div class="p-6">
                <!-- Detalles principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-folder text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nombre de la Carpeta</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['nombre'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-hashtag text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Código Digital</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['codigo'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-tag text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Categoría</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['categoria'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ubicación</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['ubicacion'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-book text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tomo</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['tomo'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">N° Hojas</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['hojas'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles adicionales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Año</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['year'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-building text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ambiente</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['ambiente'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-inbox text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bandeja</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['details']['bandeja'] }}</p>
                        </div>
                    </div>
                </div>

                @if(session('bookCreated')['esComprobante'])
                    <!-- Sección de Comprobantes -->
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-4">
                            <i class="fas fa-receipt mr-2"></i> Detalles de Comprobantes
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <i class="fas fa-arrow-right text-blue-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Inicio</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['comprobantes']['inicio'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-arrow-left text-blue-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Fin</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['comprobantes']['fin'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calculator text-blue-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Comprobantes</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ session('bookCreated')['comprobantes']['total'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="{{ route('show_book') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Ver Lista de Carpetas
                    </a>
                    @if(session('bookCreated')['esComprobante'])
                        <a href="{{ route('books.comprobantes.index', ['book' => session('bookCreated')['book_id']]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-receipt mr-2"></i> Gestionar Comprobantes
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="text-center">
                <i class="fas fa-exclamation-circle text-yellow-500 text-4xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">No hay datos de confirmación</h2>
                <p class="text-gray-600 dark:text-gray-400">Esta página solo puede ser accedida después de crear una carpeta.</p>
                <a href="{{ route('add_book') }}" class="inline-flex items-center px-4 py-2 mt-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Crear Nueva Carpeta
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
