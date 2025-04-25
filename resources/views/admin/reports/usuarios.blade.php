<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Reporte de Usuarios</title>
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
                padding: 6px;
                border: 1px solid #ddd;
                text-align: left;
                font-size: 10px;
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
                    <h1>REPORTE DE USUARIOS</h1>
                    <p>Sistema de Gestión Documental</p>
                </div>

                <img src="{{ public_path('images/bandera.png') }}" class="header-logo-right" alt="Bandera">
            </div>

            <div class="content-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>USERNAME</th>
                            <th>TELÉFONO</th>
                            <th>UNIDAD</th>
                            <th>CARGO</th>
                            <th>TIPO</th>
                            <th>REGISTRO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td style="text-align: center;">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>{{ $user->address ?? 'N/A' }}</td>
                                <td>{{ $user->position ?? 'N/A' }}</td>
                                <td style="text-align: center;">
                                    @if($user->usertype == 'admin')
                                        <span style="color: #dc3545; font-weight: bold;">{{ $user->usertype }}</span>
                                    @else
                                        <span style="color: #28a745;">{{ $user->usertype }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
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
