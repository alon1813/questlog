<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
    //

    public function index(Request $request){
        $whishlistItems = $request->user()->wishlistProducts()->get();
        return view('wishlist.index', ['items' => $whishlistItems]);
    }

    // Añadir producto a la lista de deseos
    public function add(Request $request, Product $product){
        $request->user()->wishlistProducts()->syncWithoutDetaching($product->id);
        return back()->with('success', 'Producto añadido a la lista de deseos.');
    }

    // Eliminar producto de la lista de deseos
    public function remove(Request $request, Product $product){
        $request->user()->wishlistProducts()->detach($product->id);
        return back()->with('success', 'Producto eliminado de la lista de deseos.');    
    }

}
