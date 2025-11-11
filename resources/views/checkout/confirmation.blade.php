<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Confirmación de Pedido') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-3xl font-bold mb-6 text-green-500">¡Pedido Confirmado!</h1>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <p class="mb-4 text-gray-300">Gracias por tu compra. Tu pedido <span class="font-bold text-white">{{ $order->order_number }}</span> ha sido procesado.</p>
                <p class="mb-6 text-gray-300">Recibirás un email de confirmación con los detalles y la factura en breve.</p>

                <h2 class="text-2xl font-semibold mb-4 text-white">Detalles de tu Pedido</h2>

                <div class="bg-[var(--bg-secondary)] p-4 rounded-lg shadow-md mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-400">Número de Pedido:</span>
                        <span class="font-bold text-white">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-400">Fecha del Pedido:</span>
                        <span class="text-white">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold">
                        <span class="text-white">Total:</span>
                        <span class="text-[var(--text-primary)]">{{ number_format($order->total_amount, 2) }}€</span>
                    </div>
                </div>

                <h3 class="text-xl font-semibold mb-3 text-white">Productos:</h3>
                <div class="space-y-3 mb-6">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between items-center bg-[var(--bg-secondary)] p-3 rounded-lg">
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded-md">
                                <div>
                                    <span class="font-bold text-white">{{ $item->product->name }}</span>
                                    <span class="text-gray-400 text-sm"> x {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <span class="font-bold text-white">{{ number_format($item->price * $item->quantity, 2) }}€</span>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('shop.index') }}" class="inline-block py-2 px-6 bg-blue-600 text-white font-bold rounded-lg shadow-md transition-colors hover:bg-blue-700">
                        Volver a la Tienda
                    </a>
                    <a href="{{ route('checkout.invoice.pdf', $order) }}" target="_blank" class="inline-block py-2 px-6 ml-4 bg-gray-600 text-white font-bold rounded-lg shadow-md transition-colors hover:bg-gray-700">
                        Descargar Factura PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>