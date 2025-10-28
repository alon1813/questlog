<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $items;

    public function mount(){
        $this->loadCartItems();
    }

    public function increaseQuantity($productId){
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $product = $user->wishlistProducts()->find($productId);
        if ($product) {
            $newQuantity = $product->pivot->quantity + 1;
            $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
            $this->loadCartItems();
        }
    }

    public function decreaseQuantity($productId){
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $product = $user->wishlistProducts()->find($productId);

        if ($product && $product->pivot->quantity > 1) {
            $newQuantity = $product->pivot->quantity - 1;
            $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
        }else{
            $user->wishlistProducts()->detach($productId);
        }

        $this->loadCartItems();

    }

    public function removeItem($productId){
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->wishlistProducts()->detach($productId);
        $this->loadCartItems();
    }

    private function loadCartItems(){
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->items = $user->wishlistProducts()->get();
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
