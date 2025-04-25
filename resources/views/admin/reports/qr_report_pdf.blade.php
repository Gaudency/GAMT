<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16pt;
            margin: 0;
            color: #333;
        }

        .qr-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .qr-row {
            page-break-inside: avoid;
        }

        .qr-cell {
            width: 33.33%;
            text-align: center;
            padding: 5px 2px;
            border: 1px dashed #999;
            vertical-align: top;
        }

        .qr-code {
            margin-bottom: 3px;
        }

        .qr-code img {
            max-width: 120px;
            height: auto;
        }

        .qr-code-number {
            font-weight: bold;
            color: #e53e3e;
            font-size: 12pt;
            margin: 2px 0;
        }

        .qr-info {
            font-size: 9pt;
            color: #444;
        }

        .footer {
            text-align: center;
            font-size: 8pt;
            color: #777;
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            position: fixed;
            bottom: 10mm;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Total: {{ count($books) }} códigos QR generados</p>
    </div>

    <table class="qr-grid">
        @php
            $count = 0;
            $total = count($books);
        @endphp

        @foreach($books as $book)
            @if($count % 3 == 0)
                <tr class="qr-row">
            @endif

            <td class="qr-cell">
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('qr.info', $book->id)) }}"
                         alt="QR {{ $book->N_codigo }}">
                </div>
                <div class="qr-code-number">{{ $book->N_codigo }}</div>
                <div class="qr-info">{{ $book->ubicacion }}</div>
            </td>

            @php
                $count++;
            @endphp

            @if($count % 3 == 0 || $count == $total)
                </tr>
            @endif
        @endforeach
    </table>

    <div class="footer">
        Sistema de Gestión documental GAMT - Reporte generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
