<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    @include('admin.css')

    @include('admin.header')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <!-- FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    <!-- Loader -->
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm transition-opacity">
        <div class="w-12 h-12 border-4 border-red-500 border-t-transparent rounded-full animate-spin"></div>
    </div>


    <div id="pageContent" class="min-h-screen w-full p-4 md:p-8 lg:p-12 bg-gray-100 dark:bg-gray-900 transition-all duration-300 mt-20">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 backdrop-blur-lg transition-all duration-300">
                <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-24 after:h-1 after:bg-gradient-to-r after:from-red-500 after:to-pink-500">
                    Crear Nuevo Usuario
                </h2>

                @if(session()->has('success'))
                    <div id="alertMessage" class="mb-6 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative animate-fade-in-down" role="alert">
                        <span class="block sm:inline">{{ session()->get('success') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.classList.add('animate-fade-out'); setTimeout(() => this.parentElement.remove(), 300)">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Columna 1 -->
                        <div class="space-y-4">
                            <div>
                                <label for="create_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="create_name"
                                    placeholder="Ingresa Nombre Completo"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="create_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nombre de Usuario <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="username" id="create_username"
                                    placeholder="Puede contener letras, números y caracteres especiales"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    value="{{ old('username') }}" required>
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="create_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Teléfono
                                </label>
                                <input type="text" name="phone" id="create_phone"
                                    placeholder="Número de Celular +591"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Columna 2 -->
                        <div class="space-y-4">
                            <div>
                                <label for="create_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Cargo
                                </label>
                                <input type="text" name="position" id="create_position"
                                    placeholder="Cargo o Puesto"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    value="{{ old('position') }}">
                                @error('position')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="create_usertype" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tipo de Usuario <span class="text-red-500">*</span>
                                </label>
                                <select name="usertype" id="create_usertype"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    required>
                                    <option value="" disabled {{ old('usertype') ? '' : 'selected' }}>Selecciona un tipo</option>
                                    <option value="user" {{ old('usertype') == 'user' ? 'selected' : '' }}>Usuario</option>
                                    <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                @error('usertype')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="create_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Unidad
                                </label>
                                <input type="text" name="address" id="create_address"
                                    placeholder="Unidad perteneciente al GAMT"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    value="{{ old('address') }}">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Columna 3 -->
                        <div class="space-y-4">
                            <div>
                                <label for="create_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="create_password"
                                    placeholder="Mínimo 6 caracteres"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Se requiere al menos 6 caracteres
                                </p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="create_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Confirmar Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="create_password_confirmation"
                                    placeholder="Repite la contraseña"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200"
                                    required>
                            </div>

                            <div>
                                <label for="profile_photo_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Foto de Perfil
                                </label>
                                <input type="file" name="profile_photo_path" id="profile_photo_path"
                                    class="w-full text-sm text-gray-500 dark:text-gray-300
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-red-50 file:text-red-700
                                        hover:file:bg-red-100
                                        dark:file:bg-red-900 dark:file:text-red-200
                                        dark:hover:file:bg-red-800
                                        transition-colors duration-200">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Formatos aceptados: JPG, PNG, GIF (máx. 2MB)
                                </p>
                                @error('profile_photo_path')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-4 pt-6 mt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 inline-flex items-center">
                            <i class="fas fa-user-plus mr-2"></i> Crear Usuario
                        </button>
                        <a href="{{ route('users.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.footer')

    <script>
        // Script para gestionar temas claro/oscuro
        function setDarkTheme(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            }
        }

        // Inicializar el tema según la preferencia guardada
        document.addEventListener('DOMContentLoaded', function() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true' ||
                              (localStorage.getItem('darkMode') === null &&
                               window.matchMedia('(prefers-color-scheme: dark)').matches);
            setDarkTheme(isDarkMode);

            // Validación del formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                let hasError = false;
                let errorMessage = '';

                // Validar campos requeridos
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500', 'dark:border-red-500');
                        if (field.id === 'create_name') errorMessage += 'Se requiere el nombre.\n';
                        if (field.id === 'create_username') errorMessage += 'Se requiere el nombre de usuario.\n';
                        if (field.id === 'create_usertype') errorMessage += 'Se requiere seleccionar un tipo de usuario.\n';
                        if (field.id === 'create_password') errorMessage += 'Se requiere una contraseña de al menos 6 caracteres.\n';
                        if (field.id === 'create_password_confirmation') errorMessage += 'Se requiere confirmar la contraseña.\n';
                        hasError = true;
                    } else {
                        field.classList.remove('border-red-500', 'dark:border-red-500');
                    }
                });

                // Validar longitud de contraseña
                const password = document.getElementById('create_password');
                if (password.value && password.value.length < 6) {
                    password.classList.add('border-red-500', 'dark:border-red-500');
                    errorMessage += 'La contraseña debe tener al menos 6 caracteres.\n';
                    hasError = true;
                }

                // Validar coincidencia de contraseñas
                const passwordConfirm = document.getElementById('create_password_confirmation');
                if (password.value && passwordConfirm.value && password.value !== passwordConfirm.value) {
                    passwordConfirm.classList.add('border-red-500', 'dark:border-red-500');
                    errorMessage += 'Las contraseñas no coinciden.\n';
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    // Mostrar el mensaje de error
                    alert('Por favor corrige los siguientes errores:\n' + errorMessage);
                }
            });

            // Resetear estilos al cambiar valor de campos
            const allFields = document.querySelectorAll('input, select, textarea');
            allFields.forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-500', 'dark:border-red-500');
                    }
                });
            });
        });

        // Loader animation
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            loader.classList.add('opacity-0');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        });

        // Show loader when submitting forms or clicking links
        document.addEventListener('click', function(e) {
            if ((e.target.tagName === 'A' && !e.target.classList.contains('no-loader')) ||
                (e.target.closest('form') && e.target.type === 'submit')) {
                const loader = document.getElementById('loader');
                loader.classList.remove('opacity-0');
                loader.style.display = 'flex';
            }
        });

        // Posición del contenido según la visibilidad del header
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.header-area');
            const pageContent = document.getElementById('pageContent');

            if (header && pageContent) {
                // Modificar esta línea para añadir más margen superior
                pageContent.classList.add('mt-20'); // Aumentar el margen superior

                // Función para actualizar la posición del contenido
                function updateContentPosition() {
                    if (window.innerWidth >= 1024) { // lg breakpoint
                        if (header && header.classList.contains('lg:pl-64')) {
                            pageContent.classList.add('lg:pl-64');
                        } else {
                            pageContent.classList.remove('lg:pl-64');
                        }
                    }
                }

                // Verificar el estado inicial
                updateContentPosition();

                // Observar cambios en el header
                const observer = new MutationObserver(updateContentPosition);
                observer.observe(header, { attributes: true, attributeFilter: ['class'] });

                // Actualizar cuando cambia el tamaño de la ventana
                window.addEventListener('resize', updateContentPosition);

                // Si hay un botón de toggle del sidebar
                const toggle = document.getElementById('sidebarToggle');
                if (toggle) {
                    toggle.addEventListener('click', function() {
                        setTimeout(updateContentPosition, 50);
                    });
                }
            }
        });
    </script>

    <!-- FontAwesome para íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>





