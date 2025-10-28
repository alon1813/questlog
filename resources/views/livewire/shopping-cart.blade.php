<div>
    <h1 class="text-3xl font-bold mb-2">Mi Carrito de la Compra</h1>
    <p class="text-gray-400 mb-6">Tienes {{ $items->count() }} artículo(s) guardado(s).</p>

    <div class="space-y-4">
        @forelse ($items as $item)
            <div class="flex items-center gap-4 bg-[var(--bg-secondary)] p-4 rounded-lg">
                <img src="{{ $item->image_url }}" alt="Imagen de {{ $item->name }}" class="w-24 h-24 object-cover rounded-md">
                <div class="flex-grow">
                    <h3 class="font-bold text-lg text-white">{{ $item->name }}</h3>
                    <div class="text-xl font-bold text-[var(--text-primary)]">{{ $item->price }}€</div>

                    <div class="flex items-center space-x-3 mt-2">
                        <button wire:click="decreaseQuantity({{ $item->id }})" class="px-3 py-1 bg-gray-700 rounded-md hover:bg-gray-600">-</button>
                        <span class="font-bold text-white">{{ $item->pivot->quantity }}</span>
                        <button wire:click="increaseQuantity({{ $item->id }})" class="px-3 py-1 bg-gray-700 rounded-md hover:bg-gray-600">+</button>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ $item->affiliate_url }}" target="_blank" class="px-4 py-2 text-center bg-green-600 ...">Comprar</a>
                    {{-- El botón de quitar ahora llama a un método de Livewire --}}
                    <button wire:click="removeItem({{ $item->id }})" class="w-full px-4 py-2 bg-red-600 ...">Quitar</button>
                </div>
            </div>
        @empty
            <div class="text-center bg-[var(--bg-secondary)] p-8 rounded-lg">
                <p class="text-gray-400">Tu carrito está vacío. ¡Visita la <a href="{{ route('shop.index') }}" class="font-bold hover:underline">tienda</a> para añadir productos!</p>
            </div>
        @endforelse
    </div>
</div>
