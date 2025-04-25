<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Sección de foto de perfil -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex flex-col sm:flex-row items-center">
                    <div class="mb-4 sm:mb-0 sm:mr-6">
                        <div class="w-40 h-40 rounded-full overflow-hidden bg-gray-200">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Foto de Perfil') }}</h3>
                        <form method="post" action="{{ route('perfil.photo.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="mb-4">
                                <input type="file" name="profile_photo" id="profile_photo" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                ">
                                <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                            </div>
                            <x-button>{{ __('Actualizar Foto') }}</x-button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sección de información básica -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Información Básica') }}</h3>
                    <form method="post" action="{{ route('perfil.update') }}">
                        @csrf
                        @method('put')
                        <div class="mb-4">
                            <x-label for="name" :value="__('Nombre')" />
                            <x-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="flex items-center">
                            <x-button>{{ __('Guardar') }}</x-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional de solo lectura -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Información de la Cuenta') }}</h3>
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 font-medium">{{ __('Correo Electrónico') }}</p>
                        <p class="text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 font-medium">{{ __('Rol') }}</p>
                        <p class="text-gray-900">{{ $user->role == 'admin' ? 'Administrador' : 'Usuario' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
