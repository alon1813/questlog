<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Pedido - {{ $order->order_number }}</title>
    <style>
        /* Estilos básicos para la factura */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #eee;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4CAF50; /* Un color distintivo para tu marca */
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .details {
            display: flex; /* Esto no funcionará directamente en Dompdf sin hacks o inline-block */
            justify-content: space-between;
            margin-bottom: 30px;
            overflow: hidden; /* Para contener los floats si se usan */
        }
        .details div {
            width: 48%; /* Simula un layout de dos columnas */
            float: left; /* Para layout en PDF */
            box-sizing: border-box;
        }
        .details div:last-child {
            float: right;
        }
        .details h2 {
            font-size: 18px;
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .details p {
            margin: 3px 0;
            font-size: 13px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #eee;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        .table th {
            background-color: #f8f8f8;
            color: #333;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
        }
        /* Para simular columnas en PDF con floats */
        .col-left { float: left; width: 48%; }
        .col-right { float: right; width: 48%; text-align: right; }
        .clearfix::after { content: ""; display: table; clear: both; }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Factura de Pedido</h1>
            <p>QuestLog S.L.</p>
            <p>Calle Falsa 123, Ciudad Ficticia - info@questlog.com</p>
        </div>

        <div class="details clearfix">
            <div class="col-left">
                <h2>Facturado a:</h2>
                <p><strong>{{ $order->user->name }}</strong></p>
                <p>{{ $order->user->email }}</p>
                {{-- Aquí podrías añadir la dirección de envío del usuario si la tuvieras --}}
                <p>Dirección de Envío (ejemplo): C/ Principal 1, 28001 Madrid</p>
            </div>
            <div class="col-right">
                <h2>Detalles del Pedido:</h2>
                <p><strong>Número de Pedido:</strong> {{ $order->order_number }}</p>
                <p><strong>Fecha del Pedido:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }}€</td>
                        <td>{{ number_format($item->price * $item->quantity, 2) }}€</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total del Pedido: {{ number_format($order->total_amount, 2) }}€
        </div>

        <div class="footer">
            <p>¡Gracias por tu compra en QuestLog!</p>
            <p>Este es un documento generado automáticamente y es válido sin firma.</p>
        </div>
    </div>
</body>
</html>