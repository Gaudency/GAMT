@extends('home.layouts.app')

@section('title', 'Mi Historial de Préstamos')

@section('body-class', 'leading-normal tracking-normal text-white m-6 bg-cover bg-fixed')

@section('styles')
<style>
    body {
        background-image: url("{{ asset('/images/header.png') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>
@endsection

@section('content')
    <!-- Barra de navegación -->
    @include('home.components.navbar')

    <div class="container mx-auto px-4 py-6">
        <!-- Encabezado con título -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">
                Mi Historial de
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-pink-500 to-purple-500">
                    Préstamos
                </span>
            </h1>
            <p class="text-white/70">Gestiona tus solicitudes de préstamos de carpetas</p>
        </div>

        <!-- Mensajes de alerta -->
        @if(session('message'))
            <div class="bg-white/10 backdrop-blur-sm border border-green-400/50 text-white p-4 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2 text-green-400"></i> {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-white/10 backdrop-blur-sm border border-red-400/50 text-white p-4 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2 text-red-400"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center border border-white/20
                transform transition hover:scale-105 hover:bg-white/15 duration-300 ease-in-out">
                <div class="text-3xl font-bold text-yellow-300 mb-2">
                    {{ $data->where('status', 'Applied')->count() }}
                </div>
                <div class="text-white/80">Solicitudes Pendientes</div>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center border border-white/20
                transform transition hover:scale-105 hover:bg-white/15 duration-300 ease-in-out">
                <div class="text-3xl font-bold text-green-300 mb-2">
                    {{ $data->where('status', 'Approved')->count() }}
                </div>
                <div class="text-white/80">Préstamos Aprobados</div>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center border border-white/20
                transform transition hover:scale-105 hover:bg-white/15 duration-300 ease-in-out">
                <div class="text-3xl font-bold text-red-300 mb-2">
                    {{ $data->where('status', 'Rejected')->count() }}
                </div>
                <div class="text-white/80">Solicitudes Rechazadas</div>
            </div>
        </div>

        <!-- Tabla de historial -->
        <div class="bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden shadow-xl border border-white/20">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white/20">
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Carpeta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Ubicación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($data as $borrow)
                            <tr class="hover:bg-white/5 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($borrow->book && $borrow->book->book_img)
                                            <img src="{{ asset('book/'.$borrow->book->book_img) }}"
                                                alt="{{ $borrow->book->title ?? 'Carpeta' }}"
                                                class="w-10 h-10 rounded object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded bg-white/10 backdrop-blur-sm flex items-center justify-center mr-3">
                                                <i class="fas fa-folder text-white/50"></i>
                                            </div>
                                        @endif
                                        <div class="text-sm font-medium text-white">
                                            {{ $borrow->book->title ?? 'Carpeta no disponible' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/70">
                                    {{ $borrow->book->ubicacion ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($borrow->status == 'Applied')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-white/10 backdrop-blur-sm border border-yellow-400/50 text-yellow-300">
                                            Pendiente
                                        </span>
                                    @elseif($borrow->status == 'Approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-white/10 backdrop-blur-sm border border-green-400/50 text-green-300">
                                            Aprobado
                                        </span>
                                    @elseif($borrow->status == 'Rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-white/10 backdrop-blur-sm border border-red-400/50 text-red-300">
                                            Rechazado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-white/10 backdrop-blur-sm border border-white/30 text-white/70">
                                            {{ $borrow->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/70">
                                    {{ $borrow->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($borrow->book)
                                        <a href="{{ route('books.details', $borrow->book->id) }}"
                                            class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-3 py-1 rounded-lg mr-2
                                            transform transition hover:scale-105 duration-300 ease-in-out border border-white/30">
                                            <i class="fas fa-info-circle"></i> Ver
                                        </a>
                                    @endif

                                    @if($borrow->status == 'Applied')
                                        <a href="{{ route('books.cancel', $borrow->id) }}"
                                            class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-red-300 px-3 py-1 rounded-lg
                                            transform transition hover:scale-105 duration-300 ease-in-out border border-red-400/30"
                                            onclick="return confirm('¿Estás seguro de cancelar esta solicitud?')">
                                            <i class="fas fa-times-circle"></i> Cancelar
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <i class="fas fa-history text-4xl mb-3 block text-white/50"></i>
                                    <p class="text-white/70 mb-3">No tienes historial de préstamos</p>
                                    <a href="{{ route('explore') }}"
                                        class="inline-block bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg
                                        transform transition hover:scale-105 duration-300 ease-in-out border border-white/30">
                                        Explorar carpetas disponibles
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('home.components.footer')
@endsection