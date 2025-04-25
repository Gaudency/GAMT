<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="flex items-center">
                        <div class="mr-4">
                            @if ($user->profile_photo_path)
                                <img class="h-20 w-20 rounded-full object-cover" src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}" />
                            @else
                                {{-- Asume que tienes una imagen por defecto en public/images/default-avatar.png --}}
                                <img class="h-20 w-20 rounded-full object-cover" src="{{ asset('images/default-avatar.png') }}" alt="{{ $user->name }}" />
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->username }}</p>
                            @if ($user->bio)
                                <p class="mt-2 text-sm text-gray-700">{{ $user->bio }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('perfil.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Editar Perfil') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
