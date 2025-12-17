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
    public $quantities = []; // Rastrear cantidades locales

    protected $listeners = ['productAddedToCart' => 'loadCartItems']; 

    public function getSelectedSubtotalProperty()
    {
        return $this->items
            ->whereIn('id', $this->selectedItems)
            ->sum(function ($item) {
                // Usar cantidad local si existe, sino pivot
                $quantity = $this->quantities[$item->id] ?? $item->pivot->quantity ?? 1;
                return $item->price * $quantity;
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
            
            // Inicializar cantidades locales
            foreach ($this->items as $item) {
                $this->quantities[$item->id] = $item->pivot->quantity ?? 1;
            }
        } else {
            $this->items = collect(); 
        }
    }

    public function increaseQuantity($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $currentQuantity = $this->quantities[$productId] ?? 1;
            $newQuantity = $currentQuantity + 1;
            
            // Actualizar en BD
            $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
            
            // Actualizar local
            $this->quantities[$productId] = $newQuantity;
            
            $this->loadCartItems(); 
        }
    }

    public function decreaseQuantity($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $currentQuantity = $this->quantities[$productId] ?? 1;

            if ($currentQuantity > 1) {
                $newQuantity = $currentQuantity - 1;
                $user->wishlistProducts()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
                $this->quantities[$productId] = $newQuantity;
            } else {
                // Si es 1, eliminar
                $user->wishlistProducts()->detach($productId);
                $this->selectedItems = array_diff($this->selectedItems, [$productId]);
                unset($this->quantities[$productId]);
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
            unset($this->quantities[$productId]);
            $this->loadCartItems(); 
        }
    }

    public function goToCheckout()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Por favor, selecciona al menos un producto para comprar.');
            return;
        }
        
        // Pasar cantidades actualizadas en la URL
        $queryParams = [
            'selectedItems' => implode(',', $this->selectedItems),
            'quantities' => json_encode(array_intersect_key($this->quantities, array_flip($this->selectedItems)))
        ];
        
        return $this->redirect(route('checkout.summary', $queryParams), navigate: true);
    }

    public function buySingleItemLivewire($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $wishlistItem = $user->wishlistProducts()->where('products.id', $productId)->first();

        if (!$wishlistItem) {
            session()->flash('error', 'El producto no está en tu carrito.');
            $this->loadCartItems();
            return;
        }

        // Usar cantidad local actualizada
        $quantity = $this->quantities[$productId] ?? $wishlistItem->pivot->quantity ?? 1;

        if ($quantity <= 0) {
            session()->flash('error', 'La cantidad es inválida.');
            $this->loadCartItems();
            return;
        }

        $product = $wishlistItem;

        DB::beginTransaction();
        try {
            $totalAmount = $product->price * $quantity;

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                'total_amount' => $totalAmount,
                'status' => 'completed',
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->wishlistProducts()->detach($productId);
            unset($this->quantities[$productId]);

            DB::commit();

            $order->load('user', 'items.product');
            $pdf = Pdf::loadView('invoices.order', ['order' => $order]);
            $invoiceFileName = 'factura-' . $order->order_number . '.pdf';
            
            if (!Storage::disk('public')->exists('invoices')) {
                Storage::disk('public')->makeDirectory('invoices');
            }
            
            $pdf->save(Storage::disk('public')->path('invoices/' . $invoiceFileName));
            Mail::to($user->email)->send(new OrderConfirmationMail($order, Storage::disk('public')->path('invoices/' . $invoiceFileName)));

            session()->flash('success', '¡Tu compra ha sido procesada con éxito!');
            return $this->redirect(route('checkout.confirmation', $order), navigate: true); 

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar la compra individual (Livewire): ' . $e->getMessage(), [
                'user_id' => $user->id, 
                'product_id' => $productId, 
                'exception' => $e
            ]);
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