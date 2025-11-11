<x-mail::message>
# ¡Gracias por tu pedido en QuestLog!

Hola {{ $order->user->name }},

Tu pedido con número **{{ $order->order_number }}** ha sido confirmado y procesado correctamente.

**Resumen del Pedido:**

@component('mail::table')
| Producto                       | Cantidad | Precio Unitario | Subtotal |
| :----------------------------- | :------- | :-------------- | :------- |
@foreach ($order->items as $item)
| {{ $item->product->name }}     | {{ $item->quantity }}   | {{ number_format($item->price, 2) }}€   | {{ number_format($item->price * $item->quantity, 2) }}€ |
@endforeach
| **TOTAL** |          |                 | **{{ number_format($order->total_amount, 2) }}€** |
@endcomponent

Hemos adjuntado la factura completa de tu pedido en formato PDF.

Si tienes alguna pregunta, no dudes en contactarnos.

Gracias,
El equipo de QuestLog.
</x-mail::message><x-mail::message>
# ¡Gracias por tu pedido en QuestLog!

Hola {{ $order->user->name }},

Tu pedido con número **{{ $order->order_number }}** ha sido confirmado y procesado correctamente.

**Resumen del Pedido:**

@component('mail::table')
| Producto                       | Cantidad | Precio Unitario | Subtotal |
| :----------------------------- | :------- | :-------------- | :------- |
@foreach ($order->items as $item)
| {{ $item->product->name }}     | {{ $item->quantity }}   | {{ number_format($item->price, 2) }}€   | {{ number_format($item->price * $item->quantity, 2) }}€ |
@endforeach
| **TOTAL** |          |                 | **{{ number_format($order->total_amount, 2) }}€** |
@endcomponent

Hemos adjuntado la factura completa de tu pedido en formato PDF.

Si tienes alguna pregunta, no dudes en contactarnos.

Gracias,
El equipo de QuestLog.
</x-mail::message>