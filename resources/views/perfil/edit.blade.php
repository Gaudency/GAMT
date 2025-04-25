<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Información del perfil -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Información del Perfil') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Actualiza la información de tu perfil.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('perfil.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-label for="name" value="{{ __('Nombre') }}" />
                                <x-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="username" value="{{ __('Nombre de Usuario') }}" />
                                <x-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" />
                                <x-input-error for="username" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-button>{{ __('Guardar') }}</x-button>

                                @if (session('status') === 'perfil-actualizado')
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Guardado.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Foto de perfil -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Foto de Perfil') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Actualiza tu foto de perfil.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('perfil.update-photo') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    @if ($user->profile_photo_path)
                                        <img class="h-16 w-16 object-cover rounded-full" src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}">
                                    @else
                                        {{-- Asume que tienes una imagen por defecto en public/images/default-avatar.png --}}
                                        <img class="h-16 w-16 object-cover rounded-full" src="{{ asset('images/default-avatar.png') }}" alt="{{ $user->name }}">
                                    @endif
                                </div>
                                <label class="block">
                                    <span class="sr-only">Seleccionar foto</span>
                                    <input type="file" name="photo" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-gray-50 file:text-gray-700
                                        hover:file:bg-gray-100
                                    "/>
                                </label>
                            </div>
                            <x-input-error for="photo" class="mt-2" />

                            <div class="flex items-center gap-4">
                                <x-button>{{ __('Guardar Foto') }}</x-button>

                                @if (session('status') === 'photo-updated')
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Foto actualizada.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Eliminar Cuenta -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Eliminar Cuenta') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Una vez que se elimine tu cuenta, todos tus recursos y datos se eliminarán permanentemente.') }}
                            </p>
                        </header>

                        <a href="{{ route('perfil.delete') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Ir a Eliminar Cuenta') }}
                        </a>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
