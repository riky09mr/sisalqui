<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Entregas del día {{ $fecha }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f4f4f4;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
        }
        .entrega-container {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .cliente-info {
            background-color: #f8f9fa;
            padding: 5px;
            margin-bottom: 5px;
        }
        .cliente-info h3 {
            margin: 0;
            font-size: 14px;
        }
        .cliente-info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .total {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .resumen {
            margin-top: 15px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }
        .resumen p {
            margin: 5px 0;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Entregas Programadas para el {{ $fecha }}</h2>
    </div>

    @foreach($reservas as $reserva)
        <div class="entrega-container">
            <div class="cliente-info">
                <h3>{{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</h3>
                <p><strong>Dir:</strong> {{ $reserva->direccion }} | <strong>Tel:</strong> {{ $reserva->cliente->telefono }}</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="45%">Producto</th>
                        <th width="15%">Cant.</th>
                        <th width="20%">P.Unit.</th>
                        <th width="20%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reserva->detalles->where('estado', 1) as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>${{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total">
                        <td colspan="3" style="text-align: right;">Total:</td>
                        <td>${{ number_format($reserva->precio_total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if(!$loop->last)
            <hr style="border: 0; border-top: 1px dashed #ccc; margin: 10px 0;">
        @endif
    @endforeach

    <div class="resumen">
        <p>Total de Entregas: {{ $reservas->count() }}</p>
        <p>Monto Total del Día: ${{ number_format($reservas->sum('precio_total'), 2) }}</p>
    </div>
</body>
</html>
