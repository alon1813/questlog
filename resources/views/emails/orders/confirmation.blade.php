<x-mail::message>
# ✅ ¡Gracias por tu pedido, {{ $userName }}!

Tu pedido **#{{ $order->order_number }}** ha sido confirmado y procesado correctamente.

---

## 📦 Resumen del Pedido

**Fecha del Pedido:** {{ $order->created_at->format('d/m/Y H:i') }}

@component('mail::table')
| Producto | Cantidad | Precio Unit. | Subtotal |
|:---------|:--------:|:------------:|:--------:|
@foreach ($order->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | {{ number_format($item->price, 2) }}€ | {{ number_format($item->price * $item->quantity, 2) }}€ |
@endforeach
| | | **TOTAL** | **{{ number_format($order->total_amount, 2) }}€** |
@endcomponent

---

Hemos adjuntado la factura completa de tu pedido en formato PDF.

@component('mail::button', ['url' => route('checkout.confirmation', $order)])
Ver Detalles del Pedido
@endcomponent

Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.

Gracias por confiar en QuestLog,<br>
El equipo de {{ config('app.name') }}

---

<small style="color: #999;">
Este es un correo automático, por favor no respondas a este mensaje.
</small>
</x-mail::message>