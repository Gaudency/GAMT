<!DOCTYPE html>
<html class="h-full">
<head>
    @include('admin.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Tailwind ya incluye la mayor parte de lo que necesitamos -->
    <script>
        // Asegurarnos que el modo oscuro se aplica correctamente
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        .badge {
            padding: 0.5em 1em;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        .badge-success {
            background-color: #10B981;
            color: white;
        }
        .badge-danger {
            background-color: #EF4444;
            color: white;
        }
        .btn-warning {
            background-color: #F59E0B;
            color: white;
        }
        .btn-success {
            background-color: #10B981;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-full transition-colors duration-300">
    @include('admin.header')
    <div class="flex">
        @include('admin.sidebar')

        <div class="w-full p-5 lg:p-8 mt-20"> <!-- mt-20 para compensar el header fijo -->
            <div class="w-full max-w-7xl mx-auto">
                <div class="text-center md:text-left px-4 py-6 w-full lg:w-11/12 mx-auto">
                    @if(session()->has('message'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6 rounded shadow-md relative" role="alert">
                            <span>{{ session()->get('message') }}</span>
                            <button type="button" class="absolute top-0 right-0 mt-4 mr-4 text-green-700 dark:text-green-200 hover:text-green-900 dark:hover:text-green-100 transition-colors duration-300" onclick="this.parentElement.remove()">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-4 md:mb-0 transition-colors duration-300">Gestión de Categorías</h1>
                        <button type="button" class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white py-2 px-4 rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 flex items-center space-x-2" data-modal-target="addCategoryModal" data-modal-toggle="addCategoryModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Nueva Categoría</span>
                        </button>
                    </div>

                    <div class="w-full overflow-x-auto rounded-lg shadow-md">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 transition-colors duration-300">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 transition-colors duration-300">Nombre de la Categoría</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 transition-colors duration-300">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 transition-colors duration-300">Detalles</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 transition-colors duration-300">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 transition-colors duration-300">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors duration-300">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">
                                        {{ $category->cat_title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">
                                        {{ ucfirst($category->tipo) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">
                                        @if(is_array($category->detalles))
                                            {{ $category->detalles['info'] ?? 'Sin detalles' }}
                                        @elseif(is_string($category->detalles))
                                            {{ json_decode($category->detalles)->info ?? 'Sin detalles' }}
                                        @else
                                            Sin detalles
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">
                                        <span class="badge {{ $category->status === 'activo' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $category->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <button class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-1 transition-all duration-300" data-modal-target="editCategoryModal-{{ $category->id }}" data-modal-toggle="editCategoryModal-{{ $category->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Editar
                                        </button>
                                        @if($category->status === 'activo')
                                            <a onclick="return confirm('¿Estás seguro de que quieres desactivar esta categoría?')"
                                               href="{{url('cat_delete', $category->id)}}"
                                               class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Desactivar
                                            </a>
                                        @else
                                            <a onclick="return confirm('¿Estás seguro de que quieres activar esta categoría?')"
                                               href="{{url('cat_activate', $category->id)}}"
                                               class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Activar
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal de Edición (para cada categoría) -->
                    @foreach($categories as $category)
                    <div id="editCategoryModal-{{ $category->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg transition-colors duration-300">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-5 border-b rounded-t border-gray-200 dark:border-gray-700 transition-colors duration-300">
                                    <h3 class="text-xl font-medium text-gray-900 dark:text-white transition-colors duration-300">
                                        Editar Categoría
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors duration-300" data-modal-hide="editCategoryModal-{{ $category->id }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>

                                <form action="{{ url('update_category', $category->id) }}" method="POST">
                                    @csrf
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-6">
                                        <div class="mb-4">
                                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors duration-300" for="cat_title">
                                                NOMBRE DE LA CATEGORÍA
                                            </label>
                                            <input id="cat_title" type="text" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition-colors duration-300" name="cat_title" value="{{ $category->cat_title }}" required>
                                        </div>
                                        <script>
                                            document.getElementById("cat_title").addEventListener("input", function() {
                                                this.value = this.value.toUpperCase();
                                            });
                                        </script>

                                        <div class="mb-4">
                                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors duration-300" for="tipo">
                                                TIPO
                                            </label>
                                            <select id="tipo" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition-colors duration-300" name="tipo" required>
                                                <option value="general" {{ $category->tipo == 'general' ? 'selected' : '' }}>General</option>
                                                <option value="comprobante" {{ $category->tipo == 'comprobante' ? 'selected' : '' }}>Comprobante</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors duration-300" for="detalles">
                                                DETALLES
                                            </label>
                                            <textarea id="detalles" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition-colors duration-300" name="detalles" rows="3">@if(is_array($category->detalles)){{ $category->detalles['info'] ?? '' }}@else{{ is_string($category->detalles) ? (json_decode($category->detalles, true)['info'] ?? '') : '' }}@endif</textarea>
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 dark:border-gray-700 rounded-b transition-colors duration-300">
                                        <button type="button" class="text-gray-800 dark:text-white bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-300" data-modal-hide="editCategoryModal-{{ $category->id }}">Cancelar</button>

                                        <button type="submit" class="text-white bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300 transform hover:-translate-y-1">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Modal para Añadir Nueva Categoría -->
                    <div id="addCategoryModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg transition-colors duration-300">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-5 border-b rounded-t border-gray-200 dark:border-gray-700 transition-colors duration-300">
                                    <h3 class="text-xl font-medium text-gray-900 dark:text-white transition-colors duration-300">
                                        Añadir Nueva Categoría
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors duration-300" data-modal-hide="addCategoryModal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>

                                <form action="{{ url('add_category') }}" method="POST">
                                    @csrf
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-6">
                                        <div class="mb-4">
                                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors duration-300" for="new_cat_title">
                                                NOMBRE DE LA CATEGORÍA
                                            </label>
                                            <input id="new_cat_title" type="text" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition-colors duration-300" name="cat_title" required placeholder="Escriba con Cuidado el Nombre de la Categoría">
                                        </div>
                                        <script>
                                                document.getElementById("new_cat_title").addEventListener("input", function() {
                                                    this.value = this.value.toUpperCase();
                                                });
                                            </script>

                                        <div class="mb-4">
                                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors duration-300" for="new_tipo">
                                                TIPO
                                            </label>
                                            <select id="new_tipo" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition-colors duration-300" name="tipo" required>
                                                <option value="general">General</option>
                                                <option value="comprobante">Comprobante</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors duration-300" for="new_detalles">
                                                DETALLES
                                            </label>
                                            <textarea id="new_detalles" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition-colors duration-300" name="detalles" rows="3" placeholder="Escriba con Cuidado los Detalles de la Categoría"></textarea>
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 dark:border-gray-700 rounded-b transition-colors duration-300">
                                        <button type="button" class="text-gray-800 dark:text-white bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-300" data-modal-hide="addCategoryModal">Cancelar</button>

                                        <button type="submit" class="text-white bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300 transform hover:-translate-y-1">Crear Categoría</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.footer')

    <script>
    function confirmation(ev) {
        ev.preventDefault();

        // Detectar si estamos en modo oscuro usando Tailwind
        const isDarkMode = document.documentElement.classList.contains('dark');

        swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminada, no podrás recuperar esta categoría",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "",
                },
                confirm: {
                    text: "Sí, eliminar",
                    value: true,
                    visible: true,
                    className: "",
                }
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = ev.target.href;
            }
        });

        const style = document.createElement('style');
        style.textContent = `
            .swal-modal {
                background-color: ${isDarkMode ? '#1f2937' : '#ffffff'};
                border-radius: 0.5rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .swal-title, .swal-text {
                color: ${isDarkMode ? '#f3f4f6' : '#1f2937'} !important;
            }
            .swal-button--cancel, .swal-button--confirm {
                background: linear-gradient(to right, #ef4444, #db2777) !important;
                color: white !important;
                border: none !important;
                padding: 8px 16px;
                border-radius: 0.375rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            .swal-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3) !important;
            }
            .swal-icon--warning {
                border-color: #ef4444 !important;
            }
            .swal-icon--warning__body, .swal-icon--warning__dot {
                background-color: #ef4444 !important;
            }
        `;
        document.head.appendChild(style);
    }
    </script>

    <!-- Flowbite JS para modales (reemplazo de Bootstrap) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>
</html>

