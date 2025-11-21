<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ShoppingCart extends Component
{
    public $items; 
    public $selectedItems = []; 

    
    protected $listeners = ['productAddedToCart' => 'loadCartItems']; 

    
    public function getSelectedSubtotalProperty()
    {
        return $this->items
            ->whereIn('id', $this->selectedItems)
            ->sum(function ($item) {
                
                return $item->price * ($item->pivot->quantity ?? 1);
            });
    }

    
    public function mount()
    {
        $this->loadCartItems();
    }

    
    private function loadCartItems()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $this->items = $user->wishlistProducts()
                                ->withPivot('quantity')
                                ->get();
        } else {
            $this->items = collect(); 
        }
    }

    // Incrementa la cantidad de un producto
    public function increaseQuantity($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $currentQuantity = $user->wishlistProducts()->where('product_id', $productId)->first()->pivot->quantity ?? 0;
            $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $currentQuantity + 1]);
            $this->loadCartItems(); // Recargar los productos después de la actualización
        }
    }

    // Decrementa la cantidad de un producto
    public function decreaseQuantity($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $productInWishlist = $user->wishlistProducts()->where('product_id', $productId)->first();

            if ($productInWishlist && $productInWishlist->pivot->quantity > 1) {
                $newQuantity = $productInWishlist->pivot->quantity - 1;
                $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
            } else {
                // Si la cantidad es 1 o menos, eliminar el producto de la wishlist
                $user->wishlistProducts()->detach($productId);
                // Si el item fue removido, también deseleccionarlo
                $this->selectedItems = array_diff($this->selectedItems, [$productId]);
            }
            $this->loadCartItems(); // Recargar los productos después de la actualización
        }
    }

    // Elimina un producto por completo del carrito
    public function removeItem($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->wishlistProducts()->detach($productId);
            // Si el item fue removido, también deseleccionarlo
            $this->selectedItems = array_diff($this->selectedItems, [$productId]);
            $this->loadCartItems(); // Recargar los productos después de la eliminación
            // $this->emit('productRemovedFromCart'); // Puedes emitir este evento si otros componentes lo escuchan
        }
    }

    // Redirige al resumen de compra para los ítems seleccionados
    public function goToCheckout()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Por favor, selecciona al menos un producto para comprar.');
            return;
        }
        // Redirige usando el método helper de Livewire
        return $this->redirect(route('checkout.summary', ['selectedItems' => implode(',', $this->selectedItems)]), navigate: true);
    }

    // === MÉTODO PARA COMPRA INDIVIDUAL DESDE LIVEWIRE ===
    public function buySingleItemLivewire($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Encontrar el producto en la wishlist del usuario con su cantidad
        // Asegúrate de cargar el pivot para obtener la cantidad
        $wishlistItem = $user->wishlistProducts()->where('products.id', $productId)->first();

        // Asegurarse de que el producto exista en la wishlist y tenga una cantidad válida
        if (!$wishlistItem || $wishlistItem->pivot->quantity <= 0) {
            session()->flash('error', 'El producto no está en tu carrito o la cantidad es inválida.');
            $this->loadCartItems(); // Recargar por si acaso el estado es inconsistente
            return;
        }

        // Acceder al objeto Product real a través de $wishlistItem
        $product = $wishlistItem;
        $quantity = $wishlistItem->pivot->quantity;

        DB::beginTransaction();
        try {
            $totalAmount = $product->price * $quantity;

            // 2. Crear la Orden
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                'total_amount' => $totalAmount,
                'status' => 'completed',
            ]);

            // 3. Asociar el Item a la Orden
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Eliminar el producto comprado SOLO de la wishlist
            $user->wishlistProducts()->detach($productId);

            DB::commit();

            // 5. Generar PDF y enviar Email
            // Es buena práctica asegurar que OrderConfirmationMail y PDF están usando el 'order' cargado con sus relaciones
            $order->load('user', 'items.product');
            $pdf = Pdf::loadView('invoices.order', ['order' => $order]);
            $invoiceFileName = 'factura-' . $order->order_number . '.pdf';
            if (!Storage::disk('public')->exists('invoices')) {
                Storage::disk('public')->makeDirectory('invoices');
            }
            $pdf->save(Storage::disk('public')->path('invoices/' . $invoiceFileName));
            Mail::to($user->email)->send(new OrderConfirmationMail($order, Storage::disk('public')->path('invoices/' . $invoiceFileName)));

            // 6. Redirigir a la página de confirmación
            session()->flash('success', '¡Tu compra ha sido procesada con éxito!');
            return $this->redirect(route('checkout.confirmation', $order), navigate: true); // navigate: true para Livewire 3

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar la compra individual (Livewire): ' . $e->getMessage(), ['user_id' => $user->id, 'product_id' => $productId, 'exception' => $e]);
            session()->flash('error', 'Error al procesar tu compra: ' . $e->getMessage());
            $this->loadCartItems(); // Recargar el carrito por si la transacción falló a mitad y el estado es inconsistente
            return;
        }
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}