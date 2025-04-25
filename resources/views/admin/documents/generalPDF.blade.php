<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Préstamo General</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-bottom: 1px solid #ccc;
            position: relative;
            height: 80px;
        }
        .logo {
            position: absolute;
            height: 60px;
            width: auto;
        }
        .logo-left {
            left: 10px;
            top: 10px;
        }
        .logo-right {
            right: 10px;
            top: 10px;
        }
        .header-text {
            margin: 0 70px;
            padding-top: 10px;
        }
        .title-container {
            text-align: center;
            padding: 0 20px;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
        .info-header {
            margin-bottom: 10px;
        }
        .info-title {
            background-color: #1e40af;
            color: white;
            padding: 8px;
            margin: 0;
            font-size: 16px;
        }
        .info-grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 20px;
        }
        .info-grid-cell {
            width: 48%;
            vertical-align: top;
        }
        .document-details {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
        .info-row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 30%;
        }
        .info-value {
            width: 70%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        .table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .footer {
            text-align: right;
            font-size: 10px;
            color: #6b7280;
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-prestado {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-devuelto {
            background-color: #d1fae5;
            color: #059669;
        }
        .status-vencido {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .signature-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        .signature-line {
            width: 45%;
            text-align: center;
            padding: 0 20px;
            vertical-align: top;
        }
        .signature-line .line {
            border-top: 1px solid #000;
            width: 100%;
            margin: 0 auto 10px auto;
        }
        .signature-line p {
            margin: 8px 0;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/escudo.png') }}" class="logo logo-left" alt="Escudo" onerror="this.style.display='none';">
        <div class="header-text">
            <h1 style="margin: 0;">PRÉSTAMO DE DOCUMENTACIÓN GENERAL</h1>
            <p style="margin: 5px 0 0 0;">Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
        <img src="{{ public_path('images/bandera.png') }}" class="logo logo-right" alt="Bandera" onerror="this.style.display='none';">
    </div>

    <table class="info-grid-table">
        <tr>
            <td class="info-grid-cell">
                <!-- Información del Préstamo -->
                <div class="info-section">
                    <h2 class="info-title">Información del Préstamo</h2>
                    <table class="info-row-table">
                        <tr>
                            <td class="info-label">Solicitante:</td>
                            <td class="info-value">{{ $prestamo['solicitante'] ?? 'No disponible' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Estado:</td>
                            <td class="info-value">
                                <span class="status-badge status-{{ strtolower($prestamo['estado']) }}">
                                    {{ $prestamo['estado'] ?? 'No disponible' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Fecha de préstamo:</td>
                            <td class="info-value">
                                @if(isset($prestamo['fecha_prestamo']))
                                    {{ $prestamo['fecha_prestamo'] }}
                                @else
                                    No disponible
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Fecha de devolución:</td>
                            <td class="info-value">
                                @if(isset($prestamo['fecha_devolucion']) && $prestamo['fecha_devolucion'])
                                    {{ $prestamo['fecha_devolucion'] }}
                                @else
                                    Pendiente
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Gestionado por:</td>
                            <td class="info-value">{{ $prestamo['administrador'] ?? 'No disponible' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Descripción:</td>
                            <td class="info-value">{{ $prestamo['descripcion'] ?? 'Sin descripción' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Días de préstamo:</td>
                            <td class="info-value">{{ $prestamo['dias_prestamo'] ?? '0' }} día(s)</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td class="info-grid-cell">
                <!-- Información de la Carpeta -->
                <div class="info-section">
                    <h2 class="info-title">Información de la Carpeta</h2>

                    <!-- Imagen de la carpeta -->
                    @if(isset($book['image']) && $book['image'])
                    <div style="text-align: center; margin: 10px 0;">
                        <img src="{{ public_path('book/' . $book['image']) }}" alt="Imagen de la carpeta" style="max-width: 250px; max-height: 300px; border: 1px solid #e5e7eb; padding: 3px; background: white;">
                    </div>
                    @endif

                    <table class="info-row-table">
                        <tr>
                            <td class="info-label">Título:</td>
                            <td class="info-value">{{ $book['title'] ?? 'No disponible' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Código:</td>
                            <td class="info-value">{{ $book['code'] ?? 'No disponible' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Ubicación:</td>
                            <td class="info-value">{{ $book['ubicacion'] ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Año:</td>
                            <td class="info-value">{{ $book['year'] ?? 'No disponible' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Codigo fisico:</td>
                            <td class="info-value">{{ $book['tomo'] ?? 'No disponible' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Categoría:</td>
                            <td class="info-value">{{ $book['categoria'] ?? 'No especificada' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="document-details">
        <h3 style="background-color: #1e40af; color: white; padding: 8px; margin-top: 0;">Detalles del Préstamo</h3>

        <table class="table">
            <tr>
                <th>N° Hojas</th>
                <th>N° Carpeta</th>
                <th>Folios</th>
                <th>Tipo de Préstamo</th>
                <th>Observaciones</th>
            </tr>
            <tr>
                <td style="text-align: center;">{{ $document['N_hojas'] ?? 'No especificado' }}</td>
                <td style="text-align: center;">{{ $document['N_carpeta'] ?? 'No especificado' }}</td>
                <td style="text-align: center;">{{ $document['folios'] ?? 'No especificado' }}</td>
                <td style="text-align: center;">{{ $document['loan_type'] ?? 'Estándar' }}</td>
                <td>{{ $document['observaciones'] ?? 'Sin observaciones' }}</td>
            </tr>
        </table>
    </div>

    <!-- Condiciones del préstamo -->
    <div class="document-details">
        <h3 style="background-color: #1e40af; color: white; padding: 8px; margin-top: 0;">Condiciones del Préstamo</h3>

        <p>Este préstamo está sujeto a las siguientes condiciones:</p>
        <ol>
            <li>El solicitante se compromete a devolver el documento en la fecha acordada.</li>
            <li>El documento debe ser manipulado con cuidado y devuelto en las mismas condiciones en que fue prestado.</li>
            <li>Está prohibido realizar copias del documento sin autorización previa.</li>
            <li>En caso de pérdida o daño, el solicitante asumirá la responsabilidad correspondiente.</li>
        </ol>
    </div>

    <!-- Espacio adicional antes de las firmas -->
    <div style="height: 60px;"></div>

    <!-- Firmas -->
    <table class="signature-container" cellspacing="0" width="100%">
        <tr>
            <td class="signature-line" width="45%">
                <div class="line"></div>
                <p style="margin-top: 15px;"><strong>ENTREGA</strong></p>
                <p>{{ $prestamo['administrador'] ?? 'Usuario del sistema' }}</p>
            </td>
            <td width="10%"></td>
            <td class="signature-line" width="45%">
                <div class="line"></div>
                <p style="margin-top: 15px;"><strong>RECIBE</strong></p>
                <p>{{ $prestamo['solicitante'] ?? 'Solicitante' }}</p>
            </td>
        </tr>
    </table>

    <!-- Espacio adicional después de las firmas -->
    <div style="height: 30px;"></div>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}</p>
        <p>Este documento es oficial y de uso exclusivo de la institución.</p>
    </div>
</body>
</html>
