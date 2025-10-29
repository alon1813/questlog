<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function show(Item $item)
    {
        // Aquí puedes cargar relaciones adicionales para el ítem si las necesitas
        // $item->load('genre', 'platform');
        return view('items.show', ['item' => $item]);
    }
}
