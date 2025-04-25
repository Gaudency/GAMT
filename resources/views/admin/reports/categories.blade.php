<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Reporte de Categorías</title>
        <style>
            @page {
                margin: 0cm 0cm;
            }

            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 10mm;
                width: 190mm;
                margin: 0 auto;
            }

            /* Encabezado con imágenes y degradado rojo */
            .header {
                text-align: center;
                margin-bottom: 20px;
                background: #f8d7da;
                border-bottom: 2px solid #dc3545;
                padding: 10px 0;
                position: relative;
                height: 80px;
            }

            .header-logo-left {
                position: absolute;
                left: 20px;
                top: 10px;
                width: 70px;
            }

            .header-logo-right {
                position: absolute;
                right: 20px;
                top: 10px;
                width: 70px;
            }

            .header-text {
                margin: 0 auto;
                padding-top: 15px;
                max-width: 60%;
            }

            .header h1 {
                color: #dc3545;
                margin: 0;
                font-size: 22px;
                font-weight: bold;
            }

            .header p {
                margin: 5px 0 0;
                color: #666;
                font-size: 14px;
            }

            h1 {
                text-align: center;
                color: #dc3545;
                margin-bottom: 20px;
            }

            /* Tabla centrada */
            .content-wrapper {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            th, td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: left;
            }

            th {
                background-color: #dc3545;
                color: white;
                text-align: center;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .footer {
                text-align: center;
                margin-top: 30px;
                font-size: 10px;
                color: #666;
                border-top: 1px solid #ddd;
                padding-top: 10px;
                width: 100%;
            }

            .filtros {
                margin-bottom: 15px;
                text-align: center;
                font-style: italic;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="{{ public_path('images/bandera.png') }}" class="header-logo-left" alt="Bandera">

                <div class="header-text">
                    <h1>REPORTE DE CATEGORÍAS</h1>
                    <p>Sistema de Gestión Documental</p>
                </div>

                <img src="{{ public_path('images/escudo.png') }}" class="header-logo-right" alt="Escudo">
            </div>

            <div class="content-wrapper">
                <div class="filtros">
                    @if(isset($filtros['tipo']) && !empty($filtros['tipo']))
                        <p>Filtro por Tipo: <strong>{{ ucfirst($filtros['tipo']) }}</strong></p>
                    @endif

                    @if(isset($filtros['status']) && !empty($filtros['status']))
                        <p>Filtro por Estado: <strong>{{ ucfirst($filtros['status']) }}</strong></p>
                    @endif
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TÍTULO</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>DETALLES</th>
                            <th>FECHA DE CREACIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td style="text-align: center;">{{ $category->id }}</td>
                                <td>{{ $category->cat_title }}</td>
                                <td style="text-align: center;">
                                    @if($category->tipo == 'comprobante')
                                        <span style="color: #dc3545; font-weight: bold;">{{ ucfirst($category->tipo) }}</span>
                                    @else
                                        <span style="color: #28a745;">{{ ucfirst($category->tipo) }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($category->status == 'activo')
                                        <span style="color: #28a745; font-weight: bold;">{{ ucfirst($category->status) }}</span>
                                    @else
                                        <span style="color: #dc3545;">{{ ucfirst($category->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->detalles)
                                        @php
                                            $detalles = is_array($category->detalles) ? $category->detalles : json_decode($category->detalles, true);
                                        @endphp
                                        @if(is_array($detalles))
                                            <ul style="margin: 0; padding-left: 15px;">
                                                @foreach($detalles as $key => $value)
                                                    <li><strong>{{ $key == 'info' ? '' : $key }}:</strong> {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ is_string($category->detalles) ? $category->detalles : json_encode($category->detalles) }}
                                        @endif
                                    @else
                                        <span style="color: #666; font-style: italic;">Sin detalles</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">{{ $category->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="footer">
                <p>Reporte generado por: {{ $currentUser }}</p>
                <p>Este reporte fue generado el {{ date('d/m/Y H:i:s') }}</p>
                <p>© {{ date('Y') }} - Sistema de Gestión Documental - Todos los derechos reservados</p>
            </div>
        </div>
    </body>
</html>
