<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $items;
    public $selectedItems = [];

    public function getSelectedSubtotalProperty()
    {
        return $this->items
            ->whereIn('id', $this->selectedItems)
            ->sum(function ($item) {
                $quantity = optional($item->pivot)->quantity ?? 1; 
                return $item->price * $quantity;
            });
    }

    public function mount(){
        $this->loadCartItems();
    }

    // public function increaseQuantity($productId){
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     $product = $user->wishlistProducts()->find($productId);
    //     if ($product) {
    //         $newQuantity = $product->pivot->quantity + 1;
    //         $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
    //         $this->loadCartItems();
    //     }
    // }

    public function increaseQuantity($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Usamos where y first() para asegurarnos de que el pivot se carga.
        $productInWishlist = $user->wishlistProducts()->where('product_id', $productId)->first();

        if ($productInWishlist) {
            $newQuantity = $productInWishlist->pivot->quantity + 1;
            $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
            $this->loadCartItems(); // Recargar los ítems para actualizar la vista
        }
    }

    // public function decreaseQuantity($productId){
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     $product = $user->wishlistProducts()->find($productId);

    //     if ($product && $product->pivot->quantity > 1) {
    //         $newQuantity = $product->pivot->quantity - 1;
    //         $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
    //     }else{
    //         $user->wishlistProducts()->detach($productId);
    //     }

    //     $this->loadCartItems();

    // }

    public function decreaseQuantity($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Obtener la cantidad actual desde el pivot
        $productInWishlist = $user->wishlistProducts()->where('product_id', $productId)->first();

        if ($productInWishlist && $productInWishlist->pivot->quantity > 1) {
            $newQuantity = $productInWishlist->pivot->quantity - 1;
            $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
        } else {
            // Si la cantidad es 1 o menos, o el producto no se encuentra, lo elimina
            $user->wishlistProducts()->detach($productId);
            // Asegurarse de que el ítem también se deselecciona si se quita del carrito
            $this->selectedItems = array_diff($this->selectedItems, [$productId]);
        }
        $this->loadCartItems(); // Recargar los ítems para actualizar la vista
    }

    public function removeItem($productId){
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->wishlistProducts()->detach($productId);
        $this->selectedItems = array_diff($this->selectedItems, [$productId]);
        $this->loadCartItems();
    }

    private function loadCartItems()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->items = $user->wishlistProducts()
            ->withPivot('quantity')
            ->get();
    }

    // public function goToCheckout()
    // {
        
    //     if (empty($this->selectedItems)) {
    //         session()->flash('error', 'No has seleccionado ningún producto para comprar.');
    //         return;
    //     }
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();
    //     $products = $user->wishlistProducts()
    //         ->whereIn('products.id', $this->selectedItems)
    //         ->get();

    //     if ($products->isEmpty()) {
    //         session()->flash('error', 'No se encontraron productos seleccionados.');
    //         return;
    //     }

    //     // Crear una orden temporal o definitiva
    //     $order = $user->orders()->create([
    //         'total' => $products->sum(fn ($p) => $p->price * $p->pivot->quantity),
    //     ]);

    //     // Asociar los productos a la orden
    //     foreach ($products as $product) {
    //         $order->items()->create([
    //             'product_id' => $product->id,
    //             'quantity' => $product->pivot->quantity,
    //             'price' => $product->price,
    //         ]);
    //     }

    //     // Generar PDF
    //     $pdf = \PDF::loadView('pdf.invoice', [
    //         'order' => $order,
    //         'products' => $products,
    //         'user' => $user,
    //     ]);

    //     $filename = 'factura-' . $order->id . '.pdf';
    //     $pdf->save(storage_path('app/public/facturas/' . $filename));

    //     // Enviar correo con la factura
    //     \Mail::to($user->email)->send(new \App\Mail\OrderInvoiceMail($order, $filename));

    //     // Limpiar los productos seleccionados (simulando que se “compraron”)
    //     $user->wishlistProducts()->detach($this->selectedItems);
    //     $this->selectedItems = [];
    //     $this->loadCartItems();

    //     session()->flash('success', 'Compra realizada correctamente. Se ha enviado tu factura por correo.');
    
    //     return redirect()->route('checkout.summary', ['selectedItems' => $this->selectedItems]);
    // }

    public function goToCheckout()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Por favor, selecciona al menos un producto para comprar.');
            return;
        }

        return redirect()->route('checkout.summary', ['selectedItems' => implode(',', $this->selectedItems)]);
    }


    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
