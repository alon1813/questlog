<?php

namespace App\Http\Controllers;

use App\Models\Activity; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {
        $user = null;
        $recentActivities = collect(); // Inicializa como colección vacía

        if (Auth::check()) {
            $user = Auth::user();
            // Si está logueado, podríamos mostrarle su propio feed o redirigirlo,
            // pero por ahora mantenemos la landing (según tu ruta actual)
            // y podríamos optar por no mostrarle el feed público.
        } else {
            // Si es un INVITADO, obtenemos las últimas actividades públicas
            $recentActivities = Activity::with('user', 'subject') // Carga relaciones
                                    ->whereIn('type', ['created_post', 'updated_list_item']) // Solo tipos públicos
                                    ->latest() // Las más recientes
                                    ->limit(3) // Muestra solo 3
                                    ->get();
        }

        $popularItems = [
            (object)['id' => 1, 'title' => 'Baldur\'s Gate 3', 'type' => 'game', 'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1086940/header.jpg?t=1695246593', 'link' => '#'],
            (object)['id' => 2, 'title' => 'Frieren: Beyond Journey\'s End', 'type' => 'anime', 'image_url' => 'https://cdn.myanimelist.net/images/anime/1988/139538.jpg', 'link' => '#'],
            (object)['id' => 3, 'title' => 'Cyberpunk 2077', 'type' => 'game', 'image_url' => 'https://image.api.playstation.com/vulcan/ap/rnd/202009/3021/B2aUYFC0qUAkN22mmkJ3urSo.png', 'link' => '#'],
            (object)['id' => 4, 'title' => 'Jujutsu Kaisen S2', 'type' => 'anime', 'image_url' => 'https://cdn.myanimelist.net/images/anime/1517/138131.jpg', 'link' => '#'],
            (object)['id' => 5, 'title' => 'Final Fantasy VII Rebirth', 'type' => 'game', 'image_url' => 'https://image.api.playstation.com/vulcan/ap/rnd/202401/1803/91984c3b96bdfde9c6b7a96665991b044ec0c7a7146fe77b.png', 'link' => '#'],
        ];

        return view('landing', compact('user', 'recentActivities', 'popularItems'));
    }
}