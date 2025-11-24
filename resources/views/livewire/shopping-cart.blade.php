<div>
    <h1 class="text-3xl font-bold mb-2 text-white">Mi Carrito de la Compra</h1>
    <p class="text-gray-400 mb-6">Tienes {{ $items->count() }} artículo(s) guardado(s).</p>

    <div class="space-y-4">
        @forelse ($items as $item)
            <div class="flex items-center gap-4 bg-[var(--bg-secondary)] p-4 rounded-lg">
                
                
                <div class="flex-shrink-0">
                    <input 
                        type="checkbox" 
                        wire:model.live="selectedItems" 
                        value="{{ $item->id }}"
                        class="h-6 w-6 bg-gray-700 border-gray-600 rounded text-indigo-600 focus:ring-indigo-500"
                    >
                </div>

                <img src="{{ $item->image_url }}" alt="Imagen de {{ $item->name }}" class="w-24 h-24 object-cover rounded-md">
                
                <div class="flex-grow">
                    <h3 class="font-bold text-lg text-white">{{ $item->name }}</h3>
                    <div class="text-xl font-bold text-[var(--text-primary)]">{{ $item->price }}€</div>

                    <div class="flex items-center space-x-3 mt-2">
                        <button wire:click="decreaseQuantity({{ $item->id }})" class="px-3 py-1 bg-gray-700 rounded-md hover:bg-gray-600 text-white">-</button>
                        <span class="font-bold text-white">{{ $item->pivot->quantity ?? 1}}</span>
                        <button wire:click="increaseQuantity({{ $item->id }})" class="px-3 py-1 bg-gray-700 rounded-md hover:bg-gray-600 text-white">+</button>
                    </div>
                </div>
                
                <div class="flex flex-col gap-2">
                    <a href="{{ $item->affiliate_url }}" target="_blank" class="px-4 py-2 text-center bg-green-600 ...">Comprar</a>
                    <button wire:click="removeItem({{ $item->id }})" class="w-full px-4 py-2 bg-red-600 ...">Quitar</button>
                </div>
            </div>
        @empty
            <div class="text-center bg-[var(--bg-secondary)] p-8 rounded-lg">
                <p class="text-gray-400">Tu carrito está vacío. ¡Visita la <a href="{{ route('shop.index') }}" class="font-bold hover:underline">tienda</a> para añadir productos!</p>
            </div>
        @endforelse
    </div>

    
    @if ($items->isNotEmpty())
        <div class="mt-8 bg-[var(--bg-secondary)] p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-4">Resumen de la selección</h2>
            
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-400">Subtotal ({{ count($selectedItems) }} ítems):</span>
                <span class="text-2xl font-bold text-white">{{ number_format($this->selectedSubtotal, 2) }}€</span>
            </div>

            <p class="text-gray-500 text-sm mb-4">
                El total final se calculará en la página de confirmación.
            </p>

            
            <button 
                wire:click="goToCheckout" 
                class="w-full py-3 px-6 bg-blue-600 text-white font-bold rounded-lg shadow-md transition-colors
                    hover:bg-blue-700
                    disabled:bg-gray-500 disabled:cursor-not-allowed"
                {{ count($selectedItems) === 0 ? 'disabled' : '' }} 
            >
                Comprar Seleccionados ({{ count($selectedItems) }})
            </button>
        </div>
    @endif
</div>