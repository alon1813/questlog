<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //
    public function index(){
        $products = Product::all();
        return view('shop.index', ['products' => $products]);
    }
}
