<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Foto de Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Actualizar Foto de Perfil') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Cambia tu foto de perfil para personalizar tu cuenta.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('perfil.photo.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="flex flex-col items-start">
                                @if ($user->profile_photo_path)
                                    <div class="flex-shrink-0 mb-4">
                                        <img class="h-32 w-32 rounded-full object-cover" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}">
                                    </div>
                                @else
                                    <div class="flex-shrink-0 mb-4">
                                        <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-3xl">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <x-label for="profile_photo" :value="__('Nueva foto')" />
                                    <input
                                        id="profile_photo"
                                        name="profile_photo"
                                        type="file"
                                        class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-gray-100 file:text-gray-700
                                        hover:file:bg-gray-200"
                                        accept="image/jpeg,image/png,image/jpg,image/gif"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                                </div>

                                <div class="mt-2 text-xs text-gray-500">
                                    {{ __('Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 6MB.') }}
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mt-6">
                                <x-button>
                                    {{ __('Guardar') }}
                                </x-button>

                                @if (session('status') === 'photo-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Guardado.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
