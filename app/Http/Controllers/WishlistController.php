<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class WishlistController extends Controller
{
    
    public function index()
    {
        
        return view('wishlist.index');
    }

    public function add(Product $product)
    {
        
        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        $currentProduct = $user->wishlistProducts()->where('product_id', $product->id)->first();

        if ($currentProduct) {
            
            $newQuantity = $currentProduct->pivot->quantity + 1;
            $user->wishlistProducts()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);
        } else {
            
            $user->wishlistProducts()->attach($product->id, ['quantity' => 1]);
        }

        return back()->with('success', 'Producto añadido al carrito.');
    }

    public function remove(Request $request, Product $product){
        $request->user()->wishlistProducts()->detach($product->id);
        return back()->with('success', 'Producto eliminado de la lista de deseos.');    
    }

    public function checkoutSummary(Request $request){
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rawSelectedProductIds = $request->query('selectedItems', []);

        if (is_string($rawSelectedProductIds)) {
            $rawSelectedProductIds = explode(',', $rawSelectedProductIds);
        }

        $rawSelectedProductIds = array_filter($rawSelectedProductIds);

        if (empty($rawSelectedProductIds)) {
            return redirect()->route('wishlist.index')->with('error', 'No has seleccionado ningún producto para el resumen de compra.');
        }

        $selectedProducts = $user->wishlistProducts()
            ->whereIn('products.id', $rawSelectedProductIds)
            ->withPivot('quantity')
            ->get();

        if ($selectedProducts->count() !== count($rawSelectedProductIds)) {
            return redirect()->route('wishlist.index')->with('error', 'Algunos productos seleccionados no se encontraron en tu carrito.');
        }

        $subTotal = $selectedProducts->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $total = $subTotal;
        
        return view('checkout.summary', [
            'selectedProducts' => $selectedProducts, 
            'subTotal' => $subTotal,
            'total' => $total,
        ]);
    }

    public function processCheckout(Request $request){
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $productIds = $request->input('product_ids', []);
        $quantities = $request->input('quantities', []);

        if (empty($productIds)) {
            return redirect()->route('wishlist.index')->with('error', 'No has seleccionado ningún producto para comprar.');
        }

        $selectedProductsInCart = $user->wishlistProducts()
                                    ->whereIn('products.id', $productIds)
                                    ->get();

        if ($selectedProductsInCart->count() !== count($productIds)) {
            return redirect()->route('wishlist.index')->with('error', 'Algunos productos seleccionados no se encontraron en tu carrito.');
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderItemsData = [];
            $productsToDetach = [];

            foreach($selectedProductsInCart as $product){
                $quantity = $quantities[$product->id] ?? $product->pivot->quantity;

                if ($quantity <= 0) {
                    throw new \Exception('Cantidad inválida para el producto: ' . $product->name);
                }

                $itemPrice = $product->price;
                $subTotalItem = $itemPrice * $quantity;
                $totalAmount += $subTotalItem;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $itemPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $productsToDetach[] = $product->id;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                'total_amount' => $totalAmount,
                'status' => 'completed',
            ]);

            $order->items()->createMany($orderItemsData);
            $user->wishlistProducts()->detach($productsToDetach);

            DB::commit(); 

            $order->load('user', 'items.product'); 
            $pdf = PDF::loadView('invoices.order', ['order' => $order]);
            $invoiceFileName = 'factura-' . $order->order_number . '.pdf';

            if (!Storage::disk('public')->exists('invoices')) {
                Storage::disk('public')->makeDirectory('invoices');
            }
            $pdf->save(Storage::disk('public')->path('invoices/' . $invoiceFileName)); // Guardar el PDF

            // Enviar Email de confirmación con el PDF adjunto
            Mail::to($user->email)->send(new OrderConfirmationMail($order, Storage::disk('public')->path('invoices/' . $invoiceFileName)));
            
            return redirect()->route('checkout.confirmation', $order)->with('success', '¡Tu pedido ha sido procesado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar la compra: ' . $e->getMessage(), ['user_id' => $user->id, 'exception' => $e]);
            return redirect()->route('wishlist.index')->with('error', 'Error al procesar la compra: ' . $e->getMessage());
        }
    }

    public function orderConfirmation(Order $order){
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            return redirect()->route('wishlist.index')->with('error', 'No tienes permiso para ver esta orden.');
        }

        $order->load('items.product');

        return view('checkout.confirmation', [
            'order' => $order,
        ]);
    }

    public function downloadInvoicePdf(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        
        if ($order->user_id !== $user->id) { 
            abort(403, 'No tienes permiso para descargar esta factura.');
        }

        $invoiceFileName = 'factura-' . $order->order_number . '.pdf';
        $invoiceRelativePath = 'invoices/' . $invoiceFileName; 

        if (Storage::disk('public')->exists($invoiceRelativePath)) {
            return response()->download(Storage::disk('public')->path($invoiceRelativePath), $invoiceFileName);
        }

        abort(404, 'La factura no pudo ser encontrada.'); 
    }
}
