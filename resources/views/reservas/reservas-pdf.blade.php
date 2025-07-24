<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reserva #{{ $reserva->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f4f4f4;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-section p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px dashed #ddd;
        }
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reserva</h1>
        <p>Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <h3>Información del Cliente</h3>
        <p><strong>Cliente:</strong> {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</p>
        <p><strong>Teléfono:</strong> {{ $reserva->cliente->telefono }}</p>
        <p><strong>Dirección de entrega:</strong> {{ $reserva->direccion_entrega }}</p>
    </div>

    <div class="info-section">
        <h3>Detalles de la Reserva</h3>
        <p><strong>Fecha de Reserva:</strong> {{ $reserva->created_at->format('d/m/Y') }}</p>
        <p><strong>Fecha de Entrega:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_entrega)->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <h3>Productos</h3>
        <table>
            <thead>
                <tr>
                    <th width="45%">Producto</th>
                    <th width="15%">Cantidad</th>
                    <th width="20%">Precio Unit.</th>
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
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total:</td>
                    <td>${{ number_format($reserva->precio_total, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Este documento es un comprobante de reserva.</p>
        <p>Para cualquier consulta, por favor contáctenos.</p>
    </div>
</body>
</html>
