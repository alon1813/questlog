<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-6">Tienda de Merchandising</h1>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900 border border-green-600 text-green-200 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="bg-[var(--bg-secondary)] rounded-lg overflow-hidden flex flex-col">
                        <img src="{{ $product->image_url }}" alt="Imagen de {{ $product->name }}" class="w-full h-64 object-cover">
                        <div class="p-4 flex flex-col flex-grow">
                            <h4 class="font-bold text-white flex-grow">{{ $product->name }}</h4>
                            <div class="text-xl font-bold my-2">{{ $product->price }}€</div>
                            
                            <livewire:wishlist-button :product="$product" :key="$product->id" />
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-400">No hay productos disponibles en la tienda en este momento.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>



