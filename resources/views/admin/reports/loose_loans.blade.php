<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Préstamos Sueltos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            height: 80px;
            padding: 10px 0;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 15px;
        }
        .header-text {
            margin: 0 auto;
            max-width: 70%;
        }
        .header-logo-left {
            position: absolute;
            left: 20px;
            top: 10px;
            width: 60px;
        }
        .header-logo-right {
            position: absolute;
            right: 20px;
            top: 10px;
            width: 60px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 4px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
        .status-loaned {
            color: #e67e22;
            font-weight: bold;
        }
        .status-returned {
            color: #27ae60;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
        .signatures-section {
            margin-top: 30px;
        }
        .signature-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .signature-container {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
        }
        .signature-info {
            width: 100%;
            margin-bottom: 5px;
            font-size: 10px;
        }
        .signature-image {
            width: 200px;
            height: 100px;
            margin: 0 auto;
            display: block;
        }
        .signature-label {
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($escudoExists)
            <img src="{{ public_path('images/escudo.png') }}" alt="Escudo" class="header-logo-left">
        @else
            <div class="header-logo-left">
                <span style="color: #dc3545; font-size: 40px;">◆</span>
            </div>
        @endif

        <div class="header-text">
            <div class="title">GOBIERNO AUTONOMO MUNICIPAL DE TOMAVE</div>
            <div class="subtitle">REPORTE DE PRÉSTAMOS SUELTOS</div>
            @if($periodoTexto)
            <div>Periodo: {{ $periodoTexto }}</div>
            @endif
        </div>

        @if($banderaExists)
            <img src="{{ public_path('images/bandera.png') }}" alt="Bandera" class="header-logo-right">
        @else
            <div class="header-logo-right">
                <span style="color: #dc3545; font-size: 40px;">◆</span>
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Título del Libro</th>
                <th>Cantidad Hojas</th>
                <th>Prestador</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @if(count($loose_loans) > 0)
                @foreach($loose_loans as $index => $loan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $loan->folder_code }}</td>
                    <td>{{ $loan->book_title }}</td>
                    <td>{{ $loan->sheets_count }}</td>
                    <td>{{ $loan->lender_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i:s') }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y H:i:s') }}</td>
                    <td class="{{ $loan->status == 'loaned' ? 'status-loaned' : 'status-returned' }}">
                        {{ $loan->status == 'loaned' ? 'Prestado' : 'Devuelto' }}
                    </td>
                    <td>{{ Str::limit($loan->description, 30) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" style="text-align: center;">No hay préstamos sueltos registrados en este periodo</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado el {{ date('d/m/Y H:i:s') }} | Total de préstamos: {{ count($loose_loans) }}</p>
        @if(!empty($filtros['mes']) || !empty($filtros['anio']))
        <p>
            Filtros aplicados:
            @if(!empty($filtros['mes']))
                Mes: {{ $filtros['mes'] }}
            @endif
            @if(!empty($filtros['anio']))
                Año: {{ $filtros['anio'] }}
            @endif
        </p>
        @endif
    </div>

    <!-- Sección de Firmas Digitales -->
    @if(count($loose_loans) > 0)
    <div class="page-break"></div>
    <div class="signatures-section">
        <div class="signature-title">FIRMAS DIGITALES DE PRÉSTAMOS</div>

        @foreach($loose_loans as $index => $loan)
            @if($loan->digital_signature)
            <div class="signature-container">
                <div class="signature-info">
                    <span><span class="signature-label">Préstamo #:</span> {{ $index + 1 }}</span>
                    <span><span class="signature-label">Código:</span> {{ $loan->folder_code }}</span>
                    <span><span class="signature-label">Prestador:</span> {{ $loan->lender_name }}</span>
                </div>
                <div class="signature-info">
                    <span><span class="signature-label">Título:</span> {{ Str::limit($loan->book_title, 30) }}</span>
                    <span><span class="signature-label">Fecha:</span> {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i:s') }}</span>
                    <span><span class="signature-label">Estado:</span> {{ $loan->status == 'loaned' ? 'Prestado' : 'Devuelto' }}</span>
                </div>
                <img src="{{ $loan->digital_signature }}" alt="Firma Digital" class="signature-image">
            </div>

            <!-- Agregar un salto de página cada 3 firmas -->
            @if(($index + 1) % 3 == 0 && $index < count($loose_loans) - 1)
            <div class="page-break"></div>
            @endif
            @endif
        @endforeach
    </div>
    @endif
</body>
</html>
