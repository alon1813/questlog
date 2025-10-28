<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistButton extends Component
{
    public Product $product;
    public bool $isInWishlist;

    // public function mount()
    // {
    //     if (Auth::check()) {
    //         /** @var \App\Models\User $user */
    //         $user = Auth::user();
    //         // Comprueba si el producto ya está en la lista de deseos del usuario
    //         $this->isInWishlist = $user->wishlistProducts()->where('product_id', $this->product->id)->exists();
    //     } else {
    //         $this->isInWishlist = false;
    //     }
    // }

    public function addToWishlist()
    {
        if (!Auth::check()) {
            // Si el usuario no está logueado, lo redirige al login
            return $this->redirect(route('login'), navigate: true);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $existingProduct = $user->wishlistProducts()->where('product_id', $this->product->id)->first();



        if ($existingProduct) {
            // Si ya está, lo quita
            $newQuantity = $existingProduct->pivot->quantity + 1;
            $user->wishlistProducts()->updateExistingPivot($this->product->id, ['quantity' => $newQuantity]);
            
        } else {
            // Si no está, lo añade
            $user->wishlistProducts()->attach($this->product->id, ['quantity' => 1]);
            
        }
        Log::info('Intentando emitir evento product-added');
        $this->dispatch('product-added', productName: $this->product->name);
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
