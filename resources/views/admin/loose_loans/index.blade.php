<!DOCTYPE html>
<html lang="es">
<head>
    @include('admin.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Tailwind ya incluye la mayor parte de lo que necesitamos -->
    <script>
        // Asegurarnos que el modo oscuro se aplica correctamente
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-full transition-colors duration-300">
    @include('admin.header')
    <div class="flex mt-16">
        @include('admin.sidebar')

        <div class="flex-1 p-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Préstamos Sueltos</h1>
                    <a href="{{ route('loose-loans.create') }}" class="bg-gradient-to-r from-blue-500 via-white to-blue-500 hover:from-blue-600 hover:to-blue-600 text-blue-600 font-medium py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">
                        <i class="fas fa-plus-circle mr-2"></i>Nuevo Préstamo
                    </a>
                </div>
                <!-- Nota informativa sobre la eliminación de préstamos
                <div class="bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-300 p-4 mb-4" role="alert">
                    <p><i class="fas fa-info-circle mr-2"></i> <strong>Importante:</strong> Para eliminar un préstamo, primero debe marcarlo como devuelto.</p>
                </div> -->
                @if(session()->has('success'))
                <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4" role="alert">
                    <p>{{ session()->get('success') }}</p>
                </div>
                @endif

                @if(session()->has('error'))
                <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-4" role="alert">
                    <p>{{ session()->get('error') }}</p>
                </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Título del Libro
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Prestador
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha Préstamo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha Devolución
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($loans as $loan)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ $loan->folder_code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ $loan->book_title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ $loan->lender_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($loan->status == 'loaned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Prestado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Devuelto
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('loose-loans.show', $loan->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                            <i class="fas fa-eye-slash hover:fa-eye transform hover:scale-110"></i>
                                        </a>
                                        <a href="{{ route('loose-loans.edit', $loan->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                            <i class="fas fa-pen-to-square transform hover:scale-110"></i>
                                        </a>
                                        @if($loan->status == 'loaned')
                                        <form method="POST" action="{{ route('loose-loans.return', $loan->id) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200">
                                                <i class="fas fa-circle-check transform hover:scale-110"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('loose-loans.destroy', $loan->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                                   onclick="event.preventDefault(); confirmDelete({{ $loan->id }})">
                                                <i class="fas fa-trash-can transform hover:scale-110"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No hay préstamos registrados aún
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')

    <script>
        function confirmDelete(id) {
            // Verificar el estado del préstamo
            const row = document.querySelector(`form[action="{{ route('loose-loans.destroy', '') }}/${id}"]`).closest('tr');
            const estado = row.querySelector('td:nth-child(6) span').textContent.trim();

            if (estado === 'Prestado') {
                swal({
                    title: "No se puede eliminar",
                    text: "No es posible eliminar un préstamo que no ha sido devuelto. Primero debe marcarlo como devuelto.",
                    icon: "warning",
                    button: "Entendido",
                });
            } else {
                swal({
                    title: "¿Estás seguro?",
                    text: "Una vez eliminado, no podrás recuperar este préstamo",
                    icon: "warning",
                    buttons: ["Cancelar", "Eliminar"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        document.querySelector(`form[action="{{ route('loose-loans.destroy', '') }}/${id}"]`).submit();
                    }
                });
            }
        }
    </script>
</body>
</html>



