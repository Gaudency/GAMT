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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamo de Carpeta</title>
    @include('admin.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200">
    @include('admin.header')

    <!-- Estructura principal con margen superior para respetar el header -->
    <div class="pt-16 md:pt-20">
        @extends('admin.master')

        @section('content')
        <div class="container mx-auto px-4 pt-16 md:pt-20">
            <div class="flex justify-center">
                <div class="w-full lg:w-4/5">
                    <div class="bg-white dark:bg-gray-900/95 backdrop-blur-lg shadow-xl rounded-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-gray-800 dark:to-gray-900 text-white rounded-t-lg px-6 py-4 flex justify-between items-center">
                            <h4 class="text-xl font-semibold"><i class="fas fa-handshake mr-2"></i> Préstamo de Carpeta Completa</h4>
                            <span class="bg-white/20 dark:bg-blue-500 px-3 py-1 text-sm font-semibold rounded-full">
                                <i class="fas fa-folder mr-1"></i> Préstamo General
                            </span>
                        </div>
                        <div class="p-6">
                            <!-- Información de la carpeta -->
                            <div class="bg-gray-100 dark:bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-200 dark:hover:bg-gray-800/70">
                                <h5 class="text-gray-800 dark:text-blue-400 text-lg font-semibold border-b border-gray-200 dark:border-white/10 pb-3 mb-5 flex items-center">
                                    <i class="fas fa-folder-open mr-2"></i> Información de la Carpeta
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <div class="mb-4">
                                            <span class="block text-gray-600 dark:text-gray-400 text-sm mb-1">
                                                <i class="fas fa-barcode w-5 mr-1"></i> Código digital:
                                            </span>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $book->N_codigo }}</span>
                                        </div>
                                        <div class="mb-4">
                                            <span class="block text-gray-600 dark:text-gray-400 text-sm mb-1">
                                                <i class="fas fa-book w-5 mr-1"></i> Título:
                                            </span>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $book->title }}</span>
                                        </div>
                                        <div class="mb-4">
                                            <span class="block text-gray-600 dark:text-gray-400 text-sm mb-1">
                                                <i class="fas fa-calendar w-5 mr-1"></i> Año:
                                            </span>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $book->year }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="mb-4">
                                            <span class="block text-gray-600 dark:text-gray-400 text-sm mb-1">
                                                <i class="fas fa-map-marker-alt w-5 mr-1"></i> Ubicación fisica:
                                            </span>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $book->ubicacion }}</span>
                                        </div>
                                        <div class="mb-4">
                                            <span class="block text-gray-600 dark:text-gray-400 text-sm mb-1">
                                                <i class="fas fa-bookmark w-5 mr-1"></i> Codigo Fisico:
                                            </span>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $book->tomo }}</span>
                                        </div>
                                        <div class="mb-4">
                                            <span class="block text-gray-600 dark:text-gray-400 text-sm mb-1">
                                                <i class="fas fa-file-alt w-5 mr-1"></i> Total de Hojas:
                                            </span>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $book->N_hojas }}</span>
                                        </div>
                                        <div class="mb-4">
                                            <span class="block text-gray-600 dark:text-gray-400 text-sm mb-1">
                                                <i class="fas fa-layer-group w-5 mr-1"></i> Categoría:
                                            </span>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $book->category->cat_title }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario de préstamo -->
                            <form id="loanForm" action="{{ route('document.loan.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="tipo_prestamo" value="completo">

                                <div class="bg-gray-100 dark:bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-200 dark:hover:bg-gray-800/70">
                                    <h5 class="text-gray-800 dark:text-blue-400 text-lg font-semibold border-b border-gray-200 dark:border-white/10 pb-3 mb-5 flex items-center">
                                        <i class="fas fa-user mr-2"></i> Datos del Solicitante
                                    </h5>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <div class="mb-4">
                                                <label for="applicant_name" class="block text-gray-700 dark:text-gray-200 mb-2 font-medium">Nombre del Solicitante</label>
                                                <div class="flex">
                                                    <span class="inline-flex items-center px-3 bg-gray-200 dark:bg-blue-500/20 text-gray-600 dark:text-blue-400 border border-gray-300 dark:border-white/10 border-r-0 rounded-l-md">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                    <input type="text" id="applicant_name" name="applicant_name"
                                                        class="flex-1 bg-white dark:bg-gray-800/70 border border-gray-300 dark:border-white/10 text-gray-800 dark:text-gray-200 rounded-r-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300" required>
                                                </div>
                                                <div class="text-red-500 text-sm mt-1 hidden">Este campo es requerido</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-800/70">
                                    <h5 class="text-blue-400 text-lg font-semibold border-b border-white/10 pb-3 mb-5 flex items-center">
                                        <i class="fas fa-info-circle mr-2"></i> Detalles del Préstamo
                                    </h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <div class="mb-4">
                                                <label for="N_carpeta" class="block text-gray-200 mb-2 font-medium">Nombre de La Carpeta (opcional)</label>
                                                <div class="flex">
                                                    <span class="inline-flex items-center px-3 bg-blue-500/20 text-blue-400 border border-white/10 border-r-0 rounded-l-md">
                                                        <i class="fas fa-folder"></i>
                                                    </span>
                                                    <input type="text" id="N_carpeta" name="N_carpeta"
                                                        class="flex-1 bg-gray-800/70 border border-white/10 text-gray-200 rounded-r-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mb-4">
                                                <label for="N_hojas" class="block text-gray-200 mb-2 font-medium">Número de Hojas A Prestar (opcional)</label>
                                                <div class="flex">
                                                    <span class="inline-flex items-center px-3 bg-blue-500/20 text-blue-400 border border-white/10 border-r-0 rounded-l-md">
                                                        <i class="fas fa-file"></i>
                                                    </span>
                                                    <input type="number" id="N_hojas" name="N_hojas" min="1"
                                                        class="flex-1 bg-gray-800/70 border border-white/10 text-gray-200 rounded-r-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300">
                                                </div>
                                                <div class="text-red-500 text-sm mt-1 hidden">Este campo es requeridoo</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-800/70">
                                    <h5 class="text-blue-400 text-lg font-semibold border-b border-white/10 pb-3 mb-5 flex items-center">
                                        <i class="fas fa-calendar-alt mr-2"></i> Fechas
                                    </h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <div class="mb-4">
                                                <label for="fecha_prestamo" class="block text-gray-200 mb-2 font-medium">Fecha de Préstamo</label>
                                                <div class="flex">
                                                    <span class="inline-flex items-center px-3 bg-blue-500/20 text-blue-400 border border-white/10 border-r-0 rounded-l-md">
                                                        <i class="fas fa-calendar-plus"></i>
                                                    </span>
                                                    <input type="date" id="fecha_prestamo" name="fecha_prestamo" value="{{ date('Y-m-d') }}"
                                                        class="flex-1 bg-gray-800/70 border border-white/10 text-gray-200 rounded-r-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mb-4">
                                                <label for="fecha_devolucion" class="block text-gray-200 mb-2 font-medium">Fecha de Devolución</label>
                                                <div class="flex">
                                                    <span class="inline-flex items-center px-3 bg-blue-500/20 text-blue-400 border border-white/10 border-r-0 rounded-l-md">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </span>
                                                    <input type="date" id="fecha_devolucion" name="fecha_devolucion"
                                                        class="flex-1 bg-gray-800/70 border border-white/10 text-gray-200 rounded-r-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300" required>
                                                </div>
                                                <div class="text-red-500 text-sm mt-1 hidden">La fecha debe ser posterior a hoy</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-800/50 rounded-lg p-5 mb-6 transition-all duration-300 hover:bg-gray-800/70">
                                    <h5 class="text-blue-400 text-lg font-semibold border-b border-white/10 pb-3 mb-5 flex items-center">
                                        <i class="fas fa-comment-alt mr-2"></i> Observaciones
                                    </h5>
                                    <div>
                                        <textarea id="description" name="description" rows="3" placeholder="Ingrese cualquier observación importante sobre el préstamo..."
                                            class="w-full bg-gray-800/70 border border-white/10 text-gray-200 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition duration-300"></textarea>
                                    </div>
                                </div>

                                <div class="flex justify-center mt-8 space-x-4">
                                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 text-white rounded-full shadow-lg hover:-translate-y-1 transition-all duration-300">
                                        <i class="fas fa-save mr-2"></i> Registrar Préstamo
                                    </button>
                                    <a href="{{ url()->previous() }}" class="px-8 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white rounded-full shadow-lg hover:-translate-y-1 transition-all duration-300">
                                        <i class="fas fa-times mr-2"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Éxito -->
        <div id="successModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
                <div class="relative bg-white dark:bg-gray-900 text-gray-800 dark:text-white rounded-lg max-w-md w-full mx-auto shadow-xl">
                    <div class="p-6 text-center">
                        <div class="text-green-500 text-7xl mb-4 animate-pulse">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="text-xl font-semibold mb-2">¡Préstamo Registrado!</h4>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">La carpeta fue prestada exitosamente. Puede verificar en la sección de préstamos.</p>
                        <button type="button" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-300"
                                onclick="document.getElementById('successModal').classList.add('hidden')">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay de carga -->
        <div class="loading-overlay fixed inset-0 bg-white/70 dark:bg-black/70 backdrop-blur-sm z-50 hidden flex justify-center items-center">
            <div class="bg-white dark:bg-gray-900/90 p-8 rounded-2xl shadow-2xl text-center">
                <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-blue-500 border-r-transparent">
                    <span class="sr-only">Cargando...</span>
                </div>
                <div class="text-gray-800 dark:text-gray-200 mt-4 font-medium">Registrando préstamo...</div>
            </div>
        </div>

        @push('styles')
        <style>
            /* Estilos para inputs inválidos */
            .is-invalid {
                @apply border-red-500 dark:border-red-500;
            }

            /* Animación para el ícono de éxito */
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }

            .animate-pulse {
                animation: pulse 1.5s infinite;
            }
        </style>
        @endpush

        @push('scripts')
        <script>
        $(document).ready(function() {
            let isSubmitting = false; // Flag para prevenir múltiples envíos

            // Configurar fechas
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            const fechaDevolucion = $('#fecha_devolucion');
            fechaDevolucion.attr('min', tomorrow.toISOString().split('T')[0]);

            // Validación del formulario
            function validateForm() {
                let isValid = true;
                const requiredFields = ['applicant_name', 'fecha_devolucion'];

                requiredFields.forEach(field => {
                    const input = $(`#${field}`);
                    const value = input.val().trim();

                    if (!value) {
                        isValid = false;
                        showError(input, 'Este campo es requerido');
                    } else {
                        removeError(input);
                    }
                });

                // Validar fecha de devolución
                const selectedDate = new Date(fechaDevolucion.val());
                if (selectedDate <= today) {
                    isValid = false;
                    showError(fechaDevolucion, 'La fecha debe ser posterior a hoy');
                }

                return isValid;
            }

            function showError(input, message) {
                input.addClass('is-invalid ring-2 ring-red-500');
                const feedback = input.parent().siblings('.text-red-500');
                feedback.text(message).removeClass('hidden');
            }

            function removeError(input) {
                input.removeClass('is-invalid ring-2 ring-red-500');
                input.parent().siblings('.text-red-500').addClass('hidden');
            }

            // Prevenir doble submit en todos los formularios
            $('form').on('submit', function() {
                if ($(this).data('submitted')) {
                    return false;
                }
                $(this).data('submitted', true);
            });

            // Manejar el envío del formulario
            $('#loanForm').on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) return false; // Prevenir múltiples envíos

                if (!validateForm()) {
                    return false;
                }

                isSubmitting = true;
                const submitBtn = $('button[type="submit"]');
                submitBtn.prop('disabled', true);
                $('.loading-overlay').removeClass('hidden').addClass('flex');

                // Agregar token CSRF al header de todas las peticiones AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    timeout: 10000, // Timeout de 10 segundos
                    success: function(response) {
                        if(response.success) {
                            // Mostrar modal de éxito
                            document.getElementById('successModal').classList.remove('hidden');

                            // Redirigir después de mostrar el modal
                            setTimeout(function() {
                                window.location.replace(response.redirect); // Usar replace en lugar de href
                            }, 2000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Hubo un error al procesar el préstamo',
                                background: '#333',
                                color: '#fff'
                            });
                            resetSubmitState();
                        }
                    },
                    error: function(xhr, status, error) {
                        if (status === 'timeout') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de conexión',
                                text: 'La solicitud tardó demasiado tiempo. Por favor, inténtelo de nuevo.',
                                background: '#333',
                                color: '#fff'
                            });
                        } else if(xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for(let field in errors) {
                                const input = $(`#${field}`);
                                showError(input, errors[field][0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un error al procesar el préstamo',
                                background: '#333',
                                color: '#fff'
                            });
                        }
                        resetSubmitState();
                    }
                });
            });

            function resetSubmitState() {
                isSubmitting = false;
                $('button[type="submit"]').prop('disabled', false);
                $('.loading-overlay').removeClass('flex').addClass('hidden');
                $('form').data('submitted', false);
            }

            // Limpiar errores al escribir
            $('input, textarea').on('input', function() {
                removeError($(this));
            });

            // Manejar errores de red globales
            $(document).ajaxError(function(event, jqXHR, settings, error) {
                if (error === 'timeout') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'La conexión es inestable. Por favor, verifique su conexión a internet.',
                        background: '#333',
                        color: '#fff'
                    });
                }
                resetSubmitState();
            });
        });
        </script>
        @endpush

        <!-- Agregar meta tag CSRF -->
        @push('meta')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @endpush
    </div>
</body>
</html>
@endsection
