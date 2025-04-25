<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos para pantalla */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            color: #e53e3e;
            background: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn.bg-green-500 {
            background-color: #10b981;
            color: white;
        }

        .btn.bg-green-500:hover {
            background-color: #059669;
        }

        .btn.bg-red-500 {
            background-color: #ef4444;
            color: white;
        }

        .btn.bg-red-500:hover {
            background-color: #dc2626;
        }

        .btn i {
            margin-right: 5px;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 30px;
        }

        .qr-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            transition: transform 0.3s, box-shadow 0.3s;
            page-break-inside: avoid;
        }

        .qr-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .qr-code {
            margin-bottom: 5px;
        }

        .qr-code img {
            max-width: 120px;
            height: auto;
        }

        .qr-info {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        .qr-code-number {
            font-weight: bold;
            color: #e53e3e;
            font-size: 14px;
            margin: 2px 0;
        }

        .footer {
            text-align: center;
            margin-top: 2rem;
            color: #666;
            font-size: 12px;
        }

        /* Estilos para impresión */
        @media print {
            body {
                background-color: white;
            }

            .container {
                max-width: 100%;
                padding: 0;
            }

            .header, .screen-only, .btn {
                display: none !important;
            }

            .qr-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 3px;
            }

            .qr-item {
                box-shadow: none;
                page-break-inside: avoid;
                border: 1px dashed #999;
                padding: 6px;
                break-inside: avoid;
            }

            .qr-item:hover {
                transform: none;
                box-shadow: none;
            }

            .footer {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
            <div class="header-actions">
                <div class="flex gap-2">
                    <button onclick="window.print()" class="btn screen-only flex items-center bg-green-500 hover:bg-green-600 text-white">
                        <i class="fas fa-print mr-2"></i> Imprimir QRs
                    </button>
                    <a href="{{ request()->fullUrlWithQuery(['format' => 'pdf']) }}" class="btn screen-only flex items-center bg-red-500 hover:bg-red-600 text-white">
                        <i class="fas fa-file-pdf mr-2"></i> Descargar PDF Oficio
                    </a>
                </div>
                <a href="{{ route('reporte.index') }}" class="btn screen-only">
                    <i class="fas fa-arrow-left"></i> Volver a Reportes
                </a>
            </div>
        </div>

        <div class="bg-blue-50 text-blue-700 p-3 rounded-md mb-5 screen-only">
            <i class="fas fa-info-circle mr-2"></i> Nota: La carga de los códigos QR puede tardar unos segundos, especialmente si hay muchos. Por favor, espere mientras se generan.NO abuse de la API es libre pero no ilimitada
        </div>

        <div class="qr-grid">
            @foreach($books as $book)
            <div class="qr-item">
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('qr.info', $book->id)) }}"
                         alt="QR {{ $book->N_codigo }}">
                </div>
                <div class="qr-code-number">{{ $book->N_codigo }}</div>
                <div class="qr-info">{{ $book->ubicacion }}</div>
            </div>
            @endforeach
        </div>

        <div class="footer screen-only">
            <p>Sistema de Gestión documental GAMT &copy; {{ date('Y') }}</p>
            <p><small>QRs generados: {{ count($books) }}</small></p>
        </div>
    </div>

    <script>
        // Al cargar la página, notificar que se está generando QRs
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Generando {{ count($books) }} códigos QR para impresión');
        });
    </script>
</body>
</html>
