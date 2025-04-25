@extends('home.layouts.app')

@section('title', 'Explorar Carpetas')

@section('body-class', 'leading-normal tracking-normal text-indigo-400 m-6 bg-cover bg-fixed')

@section('content')
    <!-- Barra de navegación -->
    @include('home.components.navbar')

    <div class="h-full">
        <div class="container pt-24 md:pt-36 mx-auto flex flex-wrap flex-col items-center">
            <!-- Encabezado -->
            <h1 class="my-4 text-3xl md:text-5xl text-white opacity-75 font-bold leading-tight text-center">
                Explorar
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-pink-500 to-purple-500">
                    Carpetas
                </span>
                Documentales
            </h1>

            <!-- Filtros de búsqueda -->
            <div class="w-full max-w-4xl">
                <form action="{{ route('search') }}" method="GET"
                    class="bg-gray-900 opacity-75 shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="mb-4">
                            <label class="block text-blue-300 py-2 font-bold mb-2" for="search">
                                Buscar por título
                            </label>
                            <input type="text" name="search" id="search"
                                class="shadow appearance-none border rounded w-full p-3 text-gray-700 leading-tight
                                focus:ring transform transition hover:scale-105 duration-300 ease-in-out"
                                placeholder="Título de carpeta..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-blue-300 py-2 font-bold mb-2" for="category">
                                Categoría
                            </label>
                            <select name="category" id="category"
                                class="shadow appearance-none border rounded w-full p-3 text-gray-700 leading-tight
                                focus:ring transform transition hover:scale-105 duration-300 ease-in-out">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->cat_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4 flex items-end">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-purple-800 to-green-500 hover:from-pink-500
                                hover:to-green-500 text-white font-bold py-3 px-4 rounded focus:ring
                                transform transition hover:scale-105 duration-300 ease-in-out">
                                <i class="fas fa-search mr-2"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="w-full max-w-7xl">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($books as $book)
                        <div class="bg-gray-900 opacity-75 rounded-lg overflow-hidden shadow-lg
                            transform transition hover:scale-105 duration-300 ease-in-out">
                            <div class="relative h-48 overflow-hidden">
                                @if($book->book_img)
                                    <img src="{{ asset('book/'.$book->book_img) }}"
                                        alt="{{ $book->title }}"
                                        class="w-full h-full object-cover transform transition hover:scale-110 duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-folder-open text-4xl text-blue-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-blue-300 mb-1 truncate">{{ $book->title }}</h3>
                                <p class="text-indigo-400 text-sm mb-2">{{ $book->ubicacion }}</p>
                                <p class="text-pink-400 text-xs mb-4">
                                    <i class="fas fa-tag mr-1"></i> {{ $book->category->cat_title ?? 'Sin categoría' }}
                                </p>
                                <div class="flex space-x-2">
                                    <a href="{{ route('books.details', $book->id) }}"
                                        class="flex-1 bg-gradient-to-r from-purple-800 to-indigo-500 hover:from-pink-500
                                        hover:to-purple-500 text-white text-center py-2 px-3 rounded
                                        transform transition hover:scale-105 duration-300 ease-in-out">
                                        <i class="fas fa-info-circle mr-1"></i> Detalles
                                    </a>
                                    <a href="{{ route('books.borrow', $book->id) }}"
                                        class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 hover:from-blue-500
                                        hover:to-green-500 text-white text-center py-2 px-3 rounded
                                        transform transition hover:scale-105 duration-300 ease-in-out">
                                        <i class="fas fa-book-reader mr-1"></i> Solicitar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-gray-900 opacity-75 rounded-lg p-8 text-center">
                            <i class="fas fa-folder-open text-5xl text-blue-300 mb-4"></i>
                            <h3 class="text-xl text-white mb-2">No se encontraron carpetas</h3>
                            <p class="text-indigo-400">Intenta con otros criterios de búsqueda o explora las categorías destacadas.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('home.components.footer')
@endsection
