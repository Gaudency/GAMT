<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eliminar Cuenta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Eliminar Cuenta') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Una vez que se elimine tu cuenta, todos tus recursos y datos se eliminarán permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
                            </p>
                        </header>

                        {{-- Formulario directo para eliminar cuenta --}}
                        <form method="post" action="{{ route('perfil.destroy') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('delete')

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Por favor, ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}
                            </p>

                            <div>
                                <x-label for="password_delete_direct" value="{{ __('Contraseña') }}" class="sr-only" />

                                <x-input
                                    id="password_delete_direct"
                                    name="password"
                                    type="password"
                                    class="mt-1 block w-3/4"
                                    placeholder="{{ __('Contraseña') }}"
                                />

                                <x-input-error for="password" class="mt-2" />
                            </div>

                            <div class="flex justify-start">
                                <x-danger-button type="submit">
                                    {{ __('Eliminar Cuenta Permanentemente') }}
                                </x-danger-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
