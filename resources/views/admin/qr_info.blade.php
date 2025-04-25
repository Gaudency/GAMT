<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Carpeta - {{ $book->N_codigo }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // Detectar modo oscuro
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        /* Estilos base */
        :root {
            --bg-color: #f9fafb;
            --text-color: #1f2937;
            --border-color: #e5e7eb;
            --heading-color: #111827;
            --card-bg: #ffffff;
            --accent-color: #e53e3e;
            --accent-hover: #c53030;
        }

        .dark {
            --bg-color: #1f2937;
            --text-color: #e5e7eb;
            --border-color: #4b5563;
            --heading-color: #f9fafb;
            --card-bg: #111827;
            --accent-color: #f56565;
            --accent-hover: #e53e3e;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .container {
            padding: 1rem;
            max-width: 100%;
            margin: 0 auto;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }

        .card-header {
            padding: 1rem;
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 1rem;
        }

        .card-footer {
            padding: 1rem;
            background-color: rgba(0, 0, 0, 0.03);
            border-top: 1px solid var(--border-color);
        }

        .logo {
            font-size: 1.25rem;
            font-weight: bold;
        }

        h1 {
            font-size: 1.5rem;
            margin: 0;
            color: white;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--heading-color);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .info-value {
            color: var(--text-color);
        }

        .description {
            padding: 0.75rem;
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 0.375rem;
            margin-top: 0.5rem;
            border: 1px solid var(--border-color);
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            text-decoration: none;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
            margin-top: 1rem;
            width: 100%;
        }

        .btn:hover {
            background: linear-gradient(135deg, #c53030, #9b2c2c);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            font-size: 0.875rem;
            color: var(--text-color);
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Carpeta {{ $book->N_codigo }}</h1>
                <div class="logo">TOMAVETAIL</div>
            </div>
            <div class="card-body">
                <!-- Imagen de la carpeta -->
                @if($book->book_img)
                <div class="text-center mb-4">
                    <img src="{{ asset('book/'.$book->book_img) }}" alt="Imagen de carpeta"
                         class="max-w-full h-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700"
                         style="max-height: 200px; margin: 0 auto;">
                </div>
                @endif

                <ul class="info-list">
                    <li class="info-item">
                        <span class="info-label">TÍTULO</span>
                        <span class="info-value">{{ $book->title }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">UBICACIÓN</span>
                        <span class="info-value">{{ $book->ubicacion }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">AÑO</span>
                        <span class="info-value">{{ $book->year }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">TOMO</span>
                        <span class="info-value">{{ $book->tomo }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">CATEGORÍA</span>
                        <span class="info-value">{{ $book->category->cat_title }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">N° HOJAS</span>
                        <span class="info-value">{{ $book->N_hojas }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">DESCRIPCIÓN</span>
                        <div class="info-value description">{{ $book->description }}</div>
                    </li>
                </ul>

                @if($book->pdf_file)
                <a href="{{ asset('pdfs/' . $book->pdf_file) }}" class="btn" target="_blank">
                    <i class="fas fa-file-pdf"></i> Ver PDF
                </a>
                @else
                <div class="btn" style="background: linear-gradient(135deg, #9ca3af, #6b7280); cursor: not-allowed; opacity: 0.8;">
                    <i class="fas fa-file-pdf"></i> PDF no disponible
                </div>
                @endif
            </div>
        </div>

        <div class="footer">
            Sistema de Gestión de Carpetas &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
