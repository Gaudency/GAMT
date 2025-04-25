<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial de Actividad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Actividad Reciente') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Aquí puedes ver la actividad reciente de tu cuenta.') }}
                        </p>
                    </div>

                    <div class="mt-6 space-y-6">
                        @if (count($sessions) > 0)
                            @foreach ($sessions as $session)
                                <div class="flex items-center p-4 border rounded-lg {{ $loop->first && $session->is_current_device ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}">
                                    <div>
                                        <div class="flex items-center">
                                            @if ($session->is_current_device)
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div class="text-sm font-semibold text-gray-900">{{ __('Esta sesión') }}</div>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div class="text-sm font-semibold text-gray-900">{{ __('Otra sesión') }}</div>
                                            @endif
                                        </div>

                                        <div class="mt-2">
                                            <div class="text-xs text-gray-500">
                                                {{ __('Última actividad') }}: {{ $session->last_active }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ __('IP') }}: {{ $session->ip_address }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ __('Dispositivo') }}: {{ $session->agent }}
                                            </div>
                                        </div>
                                    </div>

                                    @if (!$session->is_current_device)
                                        <div class="ml-auto">
                                            <form method="post" action="{{ route('sessions.destroy', $session->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-button class="bg-red-600 hover:bg-red-700">
                                                    {{ __('Cerrar Sesión') }}
                                                </x-button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500">{{ __('No hay sesiones activas registradas.') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <form method="post" action="{{ route('sessions.destroy.all') }}" class="flex flex-col md:flex-row justify-between items-center">
                            @csrf
                            @method('DELETE')

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Cerrar otras sesiones') }}</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Si es necesario, puedes cerrar todas tus otras sesiones en todos tus dispositivos.') }}
                                </p>
                            </div>

                            <div class="mt-5 md:mt-0">
                                <x-button class="bg-red-600 hover:bg-red-700">
                                    {{ __('Cerrar todas las sesiones') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
