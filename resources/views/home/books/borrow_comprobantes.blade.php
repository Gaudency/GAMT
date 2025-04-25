@extends('home.layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Información de la carpeta -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">
                Solicitar Comprobantes
            </h2>
            <div class="mb-4">
                <p class="text-gray-600 dark:text-gray-300">
                    <span class="font-semibold">Carpeta:</span> {{ $data->title }}
                </p>
                <p class="text-gray-600 dark:text-gray-300">
                    <span class="font-semibold">Ubicación:</span> {{ $data->ubicacion }}
                </p>
                <p class="text-gray-600 dark:text-gray-300">
                    <span class="font-semibold">Año:</span> {{ $data->year }}
                </p>
            </div>
        </div>

        <!-- Formulario de selección de comprobantes -->
        <form action="{{ route('borrow.comprobantes', $data->id) }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            @csrf
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                    Selecciona los comprobantes que deseas solicitar:
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($comprobantes as $comprobante)
                    <div class="relative">
                        <label class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                            <input type="checkbox"
                                   name="comprobantes[]"
                                   value="{{ $comprobante->id }}"
                                   class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="text-gray-700 dark:text-gray-300">
                                Comprobante #{{ $comprobante->numero_comprobante }}
                                <br>
                                <small class="text-gray-500">{{ $comprobante->n_hojas }} hojas</small>
                            </span>
                        </label>
                    </div>
                    @endforeach
                </div>

                @error('comprobantes')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ url()->previous() }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Solicitar Comprobantes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
