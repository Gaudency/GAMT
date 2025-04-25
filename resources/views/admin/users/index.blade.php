<!DOCTYPE html>
<html lang="es" class="dark">
@include('admin.css')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <!-- FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('admin.header')
    <!---sidebar---->
    @include('admin.sidebar')
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <!-- Loader -->
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm transition-opacity">
        <div class="w-12 h-12 border-4 border-red-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
    <div id="pageContent" class="min-h-screen w-full p-20 bg-gray-100 dark:bg-gray-900 transition-all duration-300">
        <div class="container mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 backdrop-blur-lg transition-all duration-300 max-w-7xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-200 mb-8 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-24 after:h-1 after:bg-gradient-to-r after:from-red-500 after:to-pink-500">
                    Gestión de Usuarios
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

                <button type="button" class="mb-6 px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 flex items-center" onclick="openUserFormModal()">
                    <i class="fas fa-user-plus mr-2"></i> Añadir Usuario
                </button>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Unidad</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Cargo</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->profile_photo_path)
                                            <img src="{{ asset($user->profile_photo_path) }}" alt="Perfil" class="h-10 w-10 rounded-full object-cover border-2 border-red-500">
                                        @else
                                            <img src="{{ asset('admin/img/user.jpg') }}" alt="Perfil" class="h-10 w-10 rounded-full object-cover border-2 border-red-500">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $user->username }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $user->phone ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $user->address ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $user->position ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->usertype === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                            {{ ucfirst($user->usertype) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-wrap gap-2 justify-center">
                                            <button type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                                onclick="document.getElementById('editUserModal-{{ $user->id }}').classList.remove('hidden')">
                                                <i class="fas fa-edit mr-1"></i> Editar
                                            </button>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600 dark:text-gray-300">No hay usuarios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de creación de usuario -->
    <div id="userFormModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-lg shadow-xl overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Crear Nuevo Usuario
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200" onclick="closeUserFormModal()">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Entradas del formulario organizadas horizontalmente -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de Usuario</label>
                            <input type="text" name="name" id="name"
                                   placeholder="Ingrese Nombre Comleto"
                                   class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                        </div>


                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                            <input type="text" name="phone" id="phone"
                                   placeholder="Celular o telefono +591"
                                   class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unidad</label>
                            <input type="text" name="address" id="address"
                                   placeholder="Unidad Perteneciete GAMT"
                                   class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cargo</label>
                            <input type="text" name="position" id="position"
                                   placeholder="Cargo que Desempeña en GAMT"
                                   class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="usertype" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol</label>
                            <select name="usertype" id="usertype"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div>
                            <label for="profile_photo_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto de Perfil</label>
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
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-red-500 dark:text-red-400 mb-1">Usuario o Email <span class="text-red-500">*</span></label>
                            <input type="text" name="username" id="username"
                                   placeholder="nombre@ejemplo o USER"
                                   class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
                            <input type="password" name="password" id="password"
                                   placeholder="Mínimo 6 caracteres"
                                   minlength="6"
                                   class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   placeholder="Confirme la contraseña"
                                   minlength="6"
                                   class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                        </div>
                    </div>

                    <div class="flex justify-center space-x-4 pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-user-plus mr-2"></i> Guardar
                        </button>
                        <button type="button" onclick="closeUserFormModal()"
                                class="px-6 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modales de edición (dinámicos) -->
    @foreach($users as $user)
    <div id="editUserModal-{{ $user->id }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
        <div class="relative bg-white dark:bg-gray-800 w-full max-w-3xl mx-auto rounded-xl shadow-lg p-6 transition-all duration-300 mt-20">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-5">Editar Usuario</h3>
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200"
                onclick="document.getElementById('editUserModal-{{ $user->id }}').classList.add('hidden')">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Organizamos en grid de 2 columnas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Columna 1 -->
                    <div>
                        <div>
                            <label for="name_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                            <input type="text" name="name" id="name_{{ $user->id }}"
                                placeholder="Ingrese nombre completo"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="phone_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                            <input type="text" name="phone" id="phone_{{ $user->id }}"
                                placeholder="Número de teléfono"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200"
                                value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="usertype_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                            <select name="usertype" id="usertype_{{ $user->id }}"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200"
                                required>
                                <option value="user" {{ $user->usertype === 'user' ? 'selected' : '' }}>Usuario</option>
                                <option value="admin" {{ $user->usertype === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('usertype')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="username_{{ $user->id }}" class="block text-sm font-medium text-red-500 dark:text-red-400">Usuario o Email <span class="text-red-500">*</span></label>
                            <input type="text" name="username" id="username_{{ $user->id }}"
                                   placeholder="nombre@ejemplo.com o nombre_usuario"
                                   class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200"
                                   value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div>
                        <div>
                            <label for="address_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidad</label>
                            <input type="text" name="address" id="address_{{ $user->id }}"
                                placeholder="Nombre de la unidad o departamento"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200"
                                value="{{ old('address', $user->address) }}">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="position_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cargo</label>
                            <input type="text" name="position" id="position_{{ $user->id }}"
                                placeholder="Cargo o puesto"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200"
                                value="{{ old('position', $user->position) }}">
                            @error('position')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="mt-4">
                            <label for="password_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva Contraseña (Opcional)</label>
                            <input type="password" name="password" id="password_{{ $user->id }}"
                                placeholder="Mínimo 6 caracteres"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="password_confirmation_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation_{{ $user->id }}"
                                placeholder="Repita la contraseña"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:text-gray-300 transition-colors duration-200">
                        </div>
                    </div>
                </div>

                <!-- Foto de perfil en la parte inferior -->
                <div class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto de Perfil Actual</label>
                            <div class="flex items-center space-x-4">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset($user->profile_photo_path) }}" alt="Perfil" class="h-16 w-16 rounded-full object-cover border-2 border-red-500">
                                @else
                                    <img src="{{ asset('admin/img/user.jpg') }}" alt="Perfil" class="h-16 w-16 rounded-full object-cover border-2 border-red-500">
                                @endif
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->profile_photo_path ? 'Foto personalizada' : 'Foto por defecto' }}</span>
                            </div>
                        </div>

                        <div>
                            <label for="profile_photo_path_{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Actualizar Foto de Perfil</label>
                            <input type="file" name="profile_photo_path" id="profile_photo_path_{{ $user->id }}" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-red-50 file:text-red-700
                                hover:file:bg-red-100
                                dark:file:bg-red-900 dark:file:text-red-200
                                dark:hover:file:bg-red-800">
                            @error('profile_photo_path')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-center space-x-4 pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-save mr-2"></i> Actualizar
                    </button>
                    <button type="button" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        onclick="document.getElementById('editUserModal-{{ $user->id }}').classList.add('hidden')">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

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
        });

        // Efecto de carga
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            loader.classList.add('opacity-0');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        });

        // Mostrar loader cuando se envíen formularios
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM' && !e.target.classList.contains('no-loader')) {
                const loader = document.getElementById('loader');
                loader.classList.remove('opacity-0');
                loader.style.display = 'flex';
            }
        });

        // Posición del contenido según la visibilidad del header
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.header-area');
            const pageContent = document.getElementById('pageContent');

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
            if (header) {
                const observer = new MutationObserver(updateContentPosition);
                observer.observe(header, { attributes: true, attributeFilter: ['class'] });
            }

            // Actualizar cuando cambia el tamaño de la ventana
            window.addEventListener('resize', updateContentPosition);

            // Si hay un botón de toggle del sidebar
            const toggle = document.getElementById('sidebarToggle');
            if (toggle) {
                toggle.addEventListener('click', function() {
                    setTimeout(updateContentPosition, 50);
                });
            }
        });

        // Funciones para abrir/cerrar el modal
        function openUserFormModal() {
            document.getElementById('userFormModal').classList.remove('hidden');
        }

        function closeUserFormModal() {
            document.getElementById('userFormModal').classList.add('hidden');
        }

        // Si el modal debe estar oculto inicialmente
        document.addEventListener('DOMContentLoaded', function() {
            closeUserFormModal();
        });
    </script>

    <!-- FontAwesome para íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
