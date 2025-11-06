<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Item; 
use App\Models\User; 

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $searchType = $request->input('type', 'game'); // Por defecto busca juegos
        $query = $request->input('query');
        $results = [];

        // Inicializamos para evitar errores si no hay usuario autenticado o resultados
        $userItemsInCollection = collect();
        $dbItems = collect(); // Inicializamos para evitar errores si no hay query

        if ($query) {

            // --- Lógica para obtener resultados de la API (Jikan para anime, RAWG para juegos) ---
            if ($searchType === 'anime') {
                $apiUrl = "https://api.jikan.moe/v4/anime?q=" . urlencode($query) . "&limit=12";
                $response = Http::get($apiUrl);

                if ($response->successful()) {
                    $results = collect($response->json()['data'])->map(function ($anime) {
                        return [
                            'api_id' => $anime['mal_id'],
                            'type' => 'anime',
                            'title' => $anime['title'],
                            'cover_image_url' => Arr::get($anime, 'images.jpg.image_url', 'https://via.placeholder.com/300x400?text=No+Image'),
                            'episodes' => $anime['episodes'] ?? null,
                            'synopsis' => Str::limit(Arr::get($anime, 'synopsis', 'No synopsis available.'), 150),
                            'released' => strtok(Arr::get($anime, 'aired.from'), 'T') ?? 'N/A',
                        ];
                    })->all();
                }
            } else { // searchType === 'game'
                $apiKey = env('RAWG_API_KEY');
                $apiUrl = "https://api.rawg.io/api/games?key={$apiKey}&search=" . urlencode($query) . "&page_size=12";
                $response = Http::get($apiUrl);

                if ($response->successful()) {
                    $results = collect($response->json()['results'])->map(function ($game) {
                        return [
                            'api_id' => $game['id'],
                            'type' => 'game',
                            'title' => $game['name'],
                            'cover_image_url' => $game['background_image'] ?? 'https://via.placeholder.com/300x400?text=No+Image',
                            'synopsis' => 'Not available from this API.', // RAWG no suele dar synopsis directamente en la búsqueda
                            'released' => $game['released'] ?? 'N/A',
                        ];
                    })->all();
                }
            }
            // --- FIN Lógica API ---


            // --- Lógica para marcar ítems en la colección y obtener el ID del pivot ---
            if (Auth::check()) {
                /** @var \App\Models\User $user */ // <<-- ¡¡¡Esta es la línea para el tipado del IDE!!!
                $user = Auth::user();

                // Primero, obtenemos todos los Item.id de la base de datos que corresponden a los resultados de la API.
                // Esto es necesario porque el $item->id de tu BD es diferente al $api_id de la API externa.
                $apiItemIdentifiers = collect($results)->map(function ($item) {
                    return ['api_id' => (string) $item['api_id'], 'type' => $item['type']]; // Asegura que api_id sea string
                })->unique()->values()->toArray(); // Asegura valores únicos para la consulta

                // Separamos api_ids y types para las cláusulas whereIn
                $apiIdsToSearch = array_column($apiItemIdentifiers, 'api_id');
                $typesToSearch = array_column($apiItemIdentifiers, 'type');

                $dbItems = Item::whereIn('api_id', $apiIdsToSearch)
                            ->whereIn('type', $typesToSearch)
                            ->get();

                // Luego, obtenemos los ítems que el usuario tiene en su colección,
                // incluyendo el pivot, y los indexamos por 'item_id' para fácil acceso.
                $userItemsInCollection = $user->items() // Ahora el IDE sabe que $user es de tipo User
                                            ->whereIn('item_id', $dbItems->pluck('id')) // Filtramos solo los que están en los resultados actuales
                                            ->withPivot('id')
                                            ->get() // Obtenemos las instancias de Item con su pivot
                                            ->keyBy('id'); // Indexamos por el ID del modelo Item (no del pivot)
                                            // Con 'keyBy('id')', cada elemento de $userItemsInCollection es un modelo Item,
                                            // y podemos acceder a su pivot a través de $item->pivot->id
            }

            // Recorremos los resultados de la API y marcamos si el usuario ya los tiene en su colección
            foreach ($results as &$result) { // Usamos '&' para modificar el array directamente
                $result['in_collection'] = false;
                $result['user_list_item_id'] = null; // Inicializamos a null

                // Buscamos el Item local que corresponda al resultado actual de la API
                $itemInDb = $dbItems->first(function ($dbItem) use ($result) {
                    // Asegura que la comparación sea estricta en tipo también si es necesario
                    return (string) $dbItem->api_id === (string) $result['api_id'] && $dbItem->type === $result['type'];
                });

                if ($itemInDb && $userItemsInCollection->has($itemInDb->id)) {
                    $inCollectionItem = $userItemsInCollection->get($itemInDb->id);
                    $result['in_collection'] = true;
                    // Aquí obtenemos el ID del registro pivot
                    $result['user_list_item_id'] = $inCollectionItem->pivot->id;
                }
            }
            // --- FIN Lógica para marcar ítems en la colección ---
        }

        if ($request->ajax()) {
             // Si la solicitud viene de React, devolvemos solo los resultados
                return response()->json($results);
            }

        return view('search.index', [
            'results' => $results,
            'query' => $query,
            'searchType' => $searchType,
        ]);
    }
}