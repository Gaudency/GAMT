<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error del Servidor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen p-5 bg-gray-100">
        <div class="w-full max-w-xl p-8 bg-white rounded-lg shadow-md">
            <div class="text-center">
                <h1 class="mb-4 text-4xl font-bold text-red-500">
                    <i class="fas fa-exclamation-triangle"></i> Error 500
                </h1>
                <h2 class="mb-6 text-2xl text-gray-800">Error Interno del Servidor</h2>
                <p class="mb-8 text-gray-600">
                    Lo sentimos, pero ha ocurrido un error en el servidor mientras procesábamos tu solicitud.
                    Nuestro equipo técnico ha sido notificado y está trabajando para solucionarlo.
                </p>
                <div class="mt-10 flex flex-col items-center gap-4">
                    <a href="{{ url('/') }}" class="px-6 py-3 text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-home mr-2"></i> Volver a la página principal
                    </a>
                    @if(auth()->check() && auth()->user()->usertype === 'admin')
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg text-left">
                        <p class="text-sm text-gray-700 mb-2"><strong>Información técnica (solo administradores):</strong></p>
                        <p class="text-sm text-gray-700">Si el error persiste, revisa el log en <code>storage/logs/laravel.log</code> para más detalles.</p>
                        <div class="mt-2">
                            <a href="{{ route('loose-loans.index') }}" class="text-sm text-blue-600 hover:underline">
                                Volver a préstamos sueltos
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
