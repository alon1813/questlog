<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resumen del Pedido') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-3xl font-bold mb-6 text-white">Confirma tu Pedido</h1>

                @if (session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-2xl font-semibold mb-4 text-white">Productos Seleccionados</h2>
                        <div class="space-y-4">
                            @forelse ($selectedProducts as $product)
                                <div class="flex items-center gap-4 bg-[var(--bg-secondary)] p-4 rounded-lg shadow-md">
                                    <img src="{{ $product->image_url }}" alt="Imagen de {{ $product->name }}" class="w-20 h-20 object-cover rounded-md">
                                    <div class="flex-grow">
                                        <h3 class="font-bold text-lg text-white">{{ $product->name }}</h3>
                                        <p class="text-gray-400">Cantidad: {{ $product->pivot->quantity }}</p>
                                    </div>
                                    <span class="text-xl font-bold text-white">{{ number_format($product->price * $product->pivot->quantity, 2) }}€</span>
                                </div>
                            @empty
                                <p class="text-gray-400">No hay productos en tu selección.</p>
                                <a href="{{ route('cart.index') }}" class="inline-block mt-4 text-blue-400 hover:underline">Volver al carrito</a>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-[var(--bg-secondary)] p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4 text-white">Detalles del Pedido</h2>
                        
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-400">Subtotal:</span>
                            <span class="text-white">{{ number_format($subTotal, 2) }}€</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-400">Envío:</span>
                            <span class="text-white">Gratis</span>
                        </div>
                        <div class="border-t border-gray-700 my-4"></div> 
                        <div class="flex justify-between items-center text-2xl font-bold mb-6">
                            <span class="text-white">Total:</span>
                            <span class="text-[var(--text-primary)]">{{ number_format($total, 2) }}€</span>
                        </div>


                        <form action="{{ route('checkout.process') }}" method="POST"> 
                            @csrf
                            
                            @foreach ($selectedProducts as $product)
                                <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                <input type="hidden" name="quantities[{{ $product->id }}]" value="{{ $product->pivot->quantity }}">
                            @endforeach

                            
                            <button type="submit" class="w-full py-3 px-6 bg-green-600 text-white font-bold rounded-lg shadow-md transition-colors hover:bg-green-700">
                                Confirmar Compra
                            </button>
                        </form>

                        <a href="{{ route('shop.index') }}" class="block text-center mt-4 text-blue-400 hover:underline">
                            Volver al Carrito para editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>