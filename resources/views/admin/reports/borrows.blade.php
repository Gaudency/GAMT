<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Préstamos</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/escudo.png') }}" class="header-logo-left" alt="Escudo">

            <div class="header-text">
                <h1>REPORTE DE PRÉSTAMOS</h1>
                <p>Sistema de Gestión Documental</p>
            </div>

            <img src="{{ public_path('images/bandera.png') }}" class="header-logo-right" alt="Bandera">
        </div>

        <div class="content-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>USUARIO</th>
                        <th>CARPETA</th>
                        <th>FECHA SOLICITUD</th>
                        <th>FECHA DEVOLUCIÓN</th>
                        <th>ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrows as $borrow)
                    <tr>
                        <td style="text-align: center;">{{ $borrow->id }}</td>
                        <td>{{ $borrow->user->name ?? 'Usuario no disponible' }}</td>
                        <td>{{ $borrow->book->title ?? 'Carpeta no disponible' }}</td>
                        <td style="text-align: center;">{{ $borrow->created_at->format('d/m/Y H:i:s') }}</td>
                        <td style="text-align: center;">{{ $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at)->format('d/m/Y H:i:s') : 'Pendiente' }}</td>
                        <td style="text-align: center;">
                            @if($borrow->status == 'Applied')
                                <span style="color: #ffc107; font-weight: bold;">Pendiente</span>
                            @elseif($borrow->status == 'Approved')
                                <span style="color: #28a745; font-weight: bold;">Aprobado</span>
                            @elseif($borrow->status == 'Returned')
                                <span style="color: #17a2b8; font-weight: bold;">Devuelto</span>
                            @elseif($borrow->status == 'Rejected')
                                <span style="color: #dc3545; font-weight: bold;">Rechazado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
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
