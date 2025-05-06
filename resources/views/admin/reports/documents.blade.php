<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de prestamos offline</title>
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
            width: 277mm; /* Ajustado para orientación horizontal */
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
            max-width: 70%;
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

        /* Estadísticas resumen */
        .estadisticas {
            width: 100%;
            margin: 20px 0;
            text-align: center;
        }

        .estadistica-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            width: 24%;
            display: inline-block;
            margin-right: 1%;
        }

        .estadistica-titulo {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .estadistica-valor {
            font-size: 16px;
            font-weight: bold;
            color: #dc3545;
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
            font-size: 10px; /* Reducido para acomodar más columnas */
        }

        th, td {
            padding: 6px;
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

        .estado-prestado {
            color: #e3342f;
            font-weight: bold;
        }

        .estado-devuelto {
            color: #38c172;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($escudoExists)
                <img src="{{ public_path('images/escudo.png') }}" class="header-logo-left" alt="Escudo">
            @else
                <div class="header-logo-left">
                    <span style="color: #dc3545; font-size: 40px;">◆</span>
                </div>
            @endif
            <div class="header-text">
                <h1>REPORTE DE PRESTAMOS OFFLINE</h1>
                <p>Sistema de Gestión Documental</p>
                @if(!empty($periodoTexto))
                    <p><strong>{{ $periodoTexto }}</strong></p>
                @endif
            </div>
            @if($banderaExists)
                <img src="{{ public_path('images/bandera.png') }}" class="header-logo-right" alt="Bandera">
            @else
                <div class="header-logo-right">
                    <span style="color: #dc3545; font-size: 40px;">◆</span>
                </div>
            @endif
        </div>
        <!-- Estadísticas resumen -->
        <div class="estadisticas">
            <div class="estadistica-item">
                <div class="estadistica-titulo">Total de Prestamos</div>
                <div class="estadistica-valor">{{ $documents->count() }}</div>
            </div>
            <div class="estadistica-item">
                <div class="estadistica-titulo">Prestados</div>
                <div class="estadistica-valor">{{ $documents->where('status', 'Prestado')->count() }}</div>
            </div>
            <div class="estadistica-item">
                <div class="estadistica-titulo">Devueltos</div>
                <div class="estadistica-valor">{{ $documents->where('status', 'Devuelto')->count() }}</div>
            </div>
            <div class="estadistica-item">
                <div class="estadistica-titulo">Total Comprobantes Individuales</div>
                <div class="estadistica-valor" style="color: #5e35b1;">
                    @php
                        $totalIndividuales = 0;
                        foreach($documents as $doc) {
                            if($doc->comprobantes) {
                                $totalIndividuales += $doc->comprobantes->count();
                            }
                        }
                        echo $totalIndividuales;
                    @endphp
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Código Préstamo</th>
                        <th>Título Carpeta</th>
                        <th>Código Carpeta</th>
                        <th>Solicitante</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Comprobantes</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->book->title ?? 'No disponible' }}</td>
                        <td>{{ $document->book->N_codigo ?? 'No disponible' }}</td>
                        <td>{{ $document->applicant_name }}</td>
                        <td>
                            @if(isset($document->category->cat_title) && strpos(strtoupper($document->category->cat_title), 'COMPROBANTES') !== false)
                                <span style="color: #5e35b1; font-weight: bold;">{{ $document->category->cat_title }}</span>
                            @elseif(isset($document->book->category->cat_title) && strpos(strtoupper($document->book->category->cat_title), 'COMPROBANTES') !== false)
                                <span style="color: #5e35b1; font-weight: bold;">{{ $document->book->category->cat_title }}</span>
                            @else
                                {{ $document->category->cat_title ?? ($document->book->category->cat_title ?? 'Sin categoría') }}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($document->status == 'Prestado')
                                <span class="estado-prestado">{{ $document->status }}</span>
                            @elseif($document->status == 'Devuelto')
                                <span class="estado-devuelto">{{ $document->status }}</span>
                            @else
                                {{ $document->status }}
                            @endif
                        </td>
                        <td style="text-align: center; color: #5e35b1; font-weight: bold;">
                            @if($document->comprobantes && $document->comprobantes->count() > 0)
                                {{ $document->comprobantes->where('pivot.estado', 'prestado')->count() }}/{{ $document->comprobantes->count() }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $document->created_at->format('d/m/Y H:i:s') }}</td>
                        <td style="text-align: center;">{{ $document->fecha_devolucion ? date('d/m/Y H:i:s', strtotime($document->fecha_devolucion)) : 'Pendiente' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center;">No se encontraron comprobantes con los filtros seleccionados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Este reporte fue generado el {{ date('d/m/Y H:i:s') }}</p>
            <p>© {{ date('Y') }} - Sistema de Gestión Documental - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
<!--





-->
