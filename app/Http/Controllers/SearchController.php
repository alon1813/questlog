<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Item; 
use App\Models\User; 

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $searchType = $request->input('type', 'game'); 
        $query = $request->input('query');
        $results = [];
        $userItemsInCollection = collect();
        $dbItems = collect(); 

        if ($query) {
            
            $cacheKey = "search_{$searchType}_{$query}";
            
            $results = Cache::remember($cacheKey, 300, function () use ($searchType, $query) {
                return rescue(fn() => $this->fetchFromApi($searchType, $query), [], false);
            });
            
            if (Auth::check()) {
                /** @var \App\Models\User $user */ 
                $user = Auth::user();

                $apiItemIdentifiers = collect($results)->map(function ($item) {
                    return ['api_id' => (string) $item['api_id'], 'type' => $item['type']]; 
                })->unique()->values()->toArray(); 

                $apiIdsToSearch = array_column($apiItemIdentifiers, 'api_id');
                $typesToSearch = array_column($apiItemIdentifiers, 'type');

                $dbItems = Item::whereIn('api_id', $apiIdsToSearch)
                            ->whereIn('type', $typesToSearch)
                            ->get();

                $userItemsInCollection = $user->items() 
                                            ->whereIn('item_id', $dbItems->pluck('id')) 
                                            ->withPivot('id')
                                            ->get() 
                                            ->keyBy('id'); 
            }

            foreach ($results as &$result) { 
                $result['in_collection'] = false;
                $result['user_list_item_id'] = null; 

                $itemInDb = $dbItems->first(function ($dbItem) use ($result) {
                    return (string) $dbItem->api_id === (string) $result['api_id'] && $dbItem->type === $result['type'];
                });

                if ($itemInDb && $userItemsInCollection->has($itemInDb->id)) {
                    $inCollectionItem = $userItemsInCollection->get($itemInDb->id);
                    $result['in_collection'] = true;
                    $result['user_list_item_id'] = $inCollectionItem->pivot->id;
                }
            }
        }

        if ($request->ajax()) {
            return response()->json($results);
        }

        return view('search.index', [
            'results' => $results,
            'query' => $query,
            'searchType' => $searchType,
        ]);
    }

    private function fetchFromApi($searchType, $query)
    {
        if ($searchType === 'anime') {
            $apiUrl = "https://api.jikan.moe/v4/anime?q=" . urlencode($query) . "&limit=12";
            $response = Http::timeout(10)->get($apiUrl);

            if ($response->successful()) {
                return collect($response->json()['data'])->map(function ($anime) {
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
        } else { 
            $apiKey = env('RAWG_API_KEY');
            $apiUrl = "https://api.rawg.io/api/games?key={$apiKey}&search=" . urlencode($query) . "&page_size=12";
            $response = Http::timeout(10)->get($apiUrl);

            if ($response->successful()) {
                return collect($response->json()['results'])->map(function ($game) {
                    return [
                        'api_id' => $game['id'],
                        'type' => 'game',
                        'title' => $game['name'],
                        'cover_image_url' => $game['background_image'] ?? 'https://via.placeholder.com/300x400?text=No+Image',
                        'synopsis' => 'Not available from this API.', 
                        'released' => $game['released'] ?? 'N/A',
                    ];
                })->all();
            }
        }

        return [];
    }
}