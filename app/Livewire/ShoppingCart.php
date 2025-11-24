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

    public function increaseQuantity($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $currentQuantity = $user->wishlistProducts()->where('product_id', $productId)->first()->pivot->quantity ?? 0;
            $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $currentQuantity + 1]);
            $this->loadCartItems(); 
        }
    }

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
                $user->wishlistProducts()->detach($productId);
                $this->selectedItems = array_diff($this->selectedItems, [$productId]);
            }
            $this->loadCartItems(); 
        }
    }

    public function removeItem($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->wishlistProducts()->detach($productId);
            $this->selectedItems = array_diff($this->selectedItems, [$productId]);
            $this->loadCartItems(); 
        }
    }

    public function goToCheckout()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Por favor, selecciona al menos un producto para comprar.');
            return;
        }
        return $this->redirect(route('checkout.summary', ['selectedItems' => implode(',', $this->selectedItems)]), navigate: true);
    }

    public function buySingleItemLivewire($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $wishlistItem = $user->wishlistProducts()->where('products.id', $productId)->first();

        if (!$wishlistItem || $wishlistItem->pivot->quantity <= 0) {
            session()->flash('error', 'El producto no está en tu carrito o la cantidad es inválida.');
            $this->loadCartItems();
            return;
        }

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
            $this->loadCartItems(); 
            return;
        }
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}