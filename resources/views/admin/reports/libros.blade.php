<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Reporte de Carpetas{{ isset($categoria) ? ' - Categoría: '.$categoria : '' }}</title>
        <style>
            @page {
                margin: 1.5cm 1.5cm; /* Márgenes adecuados para impresión */
            }

            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                font-size: 10pt; /* Tamaño de fuente base más pequeño para mejor ajuste */
            }

            /* Encabezado con diseño más simple y centrado */
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
                margin: 0 70px; /* Espacio para las imágenes */
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

            .categoria-info {
                text-align: center;
                margin: 10px 0;
                color: #dc3545;
                font-weight: bold;
                font-size: 12pt;
            }

            /* Tabla con mejor ajuste */
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 9pt; /* Tamaño más pequeño para las tablas */
            }

            th, td {
                padding: 5px; /* Padding reducido */
                border: 1px solid #ddd;
                text-align: left;
                word-wrap: break-word; /* Permite el quiebre de palabras */
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

            /* Ajustes para columnas específicas */
            .col-codigo { width: 8%; }
            .col-titulo { width: 15%; }
            .col-ubicacion { width: 10%; }
            .col-anio { width: 6%; }
            .col-descripcion { width: 20%; }
            .col-tomo { width: 6%; }
            .col-hojas { width: 6%; }
            .col-categoria { width: 15%; }
            .col-pertenece { width: 10%; }

            /* Estilos para los estados */
            .estado-activo {
                color: green;
                font-weight: bold;
            }

            .estado-no-activo {
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

            .no-data {
                text-align: center;
                padding: 20px;
                font-style: italic;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="{{ public_path('images/escudo.png') }}" class="header-image header-logo-left" alt="Escudo">
            <div class="header-text">
                <h1>REPORTE DE CARPETAS</h1>
                <p>Sistema de Gestión Documental</p>
            </div>
            <img src="{{ public_path('images/bandera.png') }}" class="header-image header-logo-right" alt="Bandera">
        </div>

        @if(isset($categoria))
            <div class="categoria-info">
                Filtrado por categoría: {{ $categoria }}
            </div>
        @endif

        @if(count($books) > 0)
            <table>
                <thead>
                    <tr>
                        <th class="col-codigo">N° CÓDIGO</th>
                        <th class="col-titulo">TÍTULO</th>
                        <th class="col-ubicacion">UBICACIÓN</th>
                        <th class="col-anio">AÑO</th>
                        <th class="col-descripcion">DESCRIPCIÓN</th>
                        <th class="col-tomo">TOMO</th>
                        <th class="col-hojas">N° HOJAS</th>
                        <th class="col-categoria">CATEGORÍA</th>
                        <th class="col-pertenece">ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td style="text-align: center;">{{ $book->N_codigo }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->ubicacion }}</td>
                            <td style="text-align: center;">{{ $book->year }}</td>
                            <td>{{ $book->description }}</td>
                            <td style="text-align: center;">{{ $book->tomo }}</td>
                            <td style="text-align: center;">{{ $book->N_hojas }}</td>
                            <td>{{ $book->category->cat_title ?? 'Sin categoría' }}</td>
                            <td style="text-align: center;">
                                @if($book->estado == 'Prestado')
                                    <span class="estado-no-activo">No Activo</span>
                                @else
                                    <span class="estado-activo">Activo</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                No se encontraron carpetas {{ isset($categoria) ? 'para la categoría '.$categoria : '' }}.
            </div>
        @endif

        <div class="footer">
            <p>Este reporte fue generado el {{ date('d/m/Y H:i:s') }}</p>
            <p>© {{ date('Y') }} - Sistema de Gestión Documental - Todos los derechos reservados</p>
        </div>
    </body>
</html>
