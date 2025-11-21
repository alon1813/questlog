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

    public function addToWishlist()
    {
        if (!Auth::check()) {
            
            return $this->redirect(route('login'), navigate: true);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $existingProduct = $user->wishlistProducts()->where('product_id', $this->product->id)->first();

        if ($existingProduct) {
            
            $newQuantity = $existingProduct->pivot->quantity + 1;
            $user->wishlistProducts()->updateExistingPivot($this->product->id, ['quantity' => $newQuantity]);
            
        } else {
            
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
