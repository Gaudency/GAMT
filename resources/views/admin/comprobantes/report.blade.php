<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Reporte de Comprobantes</title>
        <style>
            @page {
                margin: 1.5cm 1.5cm;
            }

            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                font-size: 10pt;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
                background: #f8d7da;
                border-bottom: 2px solid #dc3545;
                padding: 10px 0;
                position: relative;
                height: 80px;
            }

            .header-image {
                position: absolute;
                height: 60px;
                width: auto;
            }

            .header-logo-left {
                left: 10px;
                top: 10px;
            }

            .header-logo-right {
                right: 10px;
                top: 10px;
            }

            .header-text {
                margin: 0 70px;
                padding-top: 10px;
            }

            .header h1 {
                color: #dc3545;
                margin: 0;
                font-size: 18pt;
                font-weight: bold;
            }

            .header p {
                margin: 5px 0 0;
                color: #666;
                font-size: 10pt;
            }

            .book-info {
                margin: 20px 0;
                padding: 10px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
            }

            .book-info h2 {
                color: #dc3545;
                margin: 0 0 10px 0;
                font-size: 14pt;
            }

            .book-info p {
                margin: 5px 0;
                font-size: 10pt;
            }

            /* Reemplazamos el flexbox con table para las estadísticas */
            .stats {
                width: 100%;
                margin: 20px 0;
            }

            .stats-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 5px;
            }

            .stat-box {
                width: 23%;
                padding: 10px;
                text-align: center;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
            }

            .stat-box h3 {
                margin: 0;
                color: #dc3545;
                font-size: 18pt;
            }

            .stat-box p {
                margin: 5px 0 0;
                color: #666;
                font-size: 9pt;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 9pt;
                table-layout: fixed;
            }

            th, td {
                padding: 5px;
                border: 1px solid #ddd;
                text-align: left;
                min-height: 40px;
            }

            th {
                background-color: #dc3545;
                color: white;
                text-align: center;
                font-size: 9pt;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr {
                page-break-inside: avoid;
                min-height: 40px;
            }

            /* Estilos para descripciones largas */
            td {
                vertical-align: top;
            }

            /* Específicamente para la columna de descripción */
            td:nth-child(4) {
                max-width: 200px;
                white-space: normal;
                word-wrap: break-word;
                word-break: break-word;
                height: auto;
                padding: 8px;
            }

            .estado-activo {
                color: green;
                font-weight: bold;
            }

            .estado-inactivo {
                color: red;
                font-weight: bold;
            }

            .footer {
                text-align: center;
                margin-top: 20px;
                font-size: 8pt;
                color: #666;
                border-top: 1px solid #ddd;
                padding-top: 5px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            @if(isset($escudoBase64) && $escudoBase64)
                <img src="{{ $escudoBase64 }}" class="header-image header-logo-left" alt="Escudo">
            @endif
            <div class="header-text">
                <h1>REPORTE DE TODOS LOS COMPROBANTES</h1>
                <p>Sistema de Gestión Documental</p>
            </div>
            @if(isset($banderaBase64) && $banderaBase64)
                <img src="{{ $banderaBase64 }}" class="header-image header-logo-right" alt="Bandera">
            @endif
        </div>

        <div class="book-info">
            <h2>Información de la Carpeta</h2>
            <p><strong>Título:</strong> {{ $book->title }}</p>
            <p><strong>Código:</strong> {{ $book->N_codigo }}</p>
            <p><strong>Categoría:</strong> {{ $book->category->cat_title }}</p>
            <p><strong>Ubicación:</strong> {{ $book->ubicacion }}</p>
            <p><strong>Año:</strong> {{ $book->year }}</p>
            <p><strong>Tomo:</strong> {{ $book->tomo }}</p>
        </div>

        <div class="stats">
            <table class="stats-table" border="0">
                <tr>
                    <td class="stat-box">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Total Comprobantes</p>
                    </td>
                    <td class="stat-box">
                        <h3>{{ $stats['disponibles'] }}</h3>
                        <p>Disponibles</p>
                    </td>
                    <td class="stat-box">
                        <h3>{{ $stats['prestados'] }}</h3>
                        <p>En Préstamo</p>
                    </td>
                    <td class="stat-box">
                        <h3>{{ $stats['total_hojas'] }}</h3>
                        <p>Total Hojas</p>
                    </td>
                </tr>
                <tr>
                    <td class="stat-box" colspan="4">
                        <h3>Bs {{ number_format($stats['total_costo'] ?? 0, 2) }}</h3>
                        <p>Costo Total Devengado</p>
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 9%;">N° Comprobante</th>
                    <th style="width: 12%;">Código</th>
                    <th style="width: 8%;">N° Hojas</th>
                    <th style="width: 12%;">Costo (Bs)</th>
                    <th style="width: 8%;">Estado</th>
                    <th style="width: 35%;">Descripción</th>
                    <th style="width: 8%;">PDF</th>
                    <th style="width: 14%;">Última Actualización</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comprobantes as $comprobante)
                <tr>
                    <td style="text-align: center;">{{ $comprobante->numero_comprobante }}</td>
                    <td style="text-align: center;">{{ $comprobante->codigo_personalizado ?: '-' }}</td>
                    <td style="text-align: center;">{{ $comprobante->n_hojas }}</td>
                    <td style="text-align: right;">{{ $comprobante->costo ? number_format($comprobante->costo, 2) : '-' }}</td>
                    <td style="text-align: center;">
                        <span class="{{ $comprobante->estado == 'activo' ? 'estado-activo' : 'estado-inactivo' }}">
                            {{ ucfirst($comprobante->estado) }}
                        </span>
                    </td>
                    <td style="word-wrap: break-word; word-break: break-all; max-width: 35%; height: auto; vertical-align: top; white-space: normal;">{{ $comprobante->descripcion ?: 'Sin descripción' }}</td>
                    <td style="text-align: center;">
                        {{ $comprobante->pdf_file ? 'Sí' : 'No' }}
                    </td>
                    <td style="text-align: center;">{{ $comprobante->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Reporte generado el {{ $fecha_generacion }}</p>
            <p>© {{ date('Y') }} - Sistema de Gestión Documental - Todos los derechos reservados</p>
        </div>
    </body>
</html>
