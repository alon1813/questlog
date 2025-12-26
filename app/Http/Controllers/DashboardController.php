<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request){
        $user = $request->user();
        $activities = Activity::with([
            'user:id,name,username,avatar_path',
            'subject'
        ])->latest()->take(20)->get();

        // $stats = [
        //     'playing' => $user->items()->where('type', 'game')->wherePivot('status', 'Jugando')->count(),
        //     'watching' => $user->items()->where('type', 'anime')->wherePivot('status', 'Jugando')->count(), 
        //     'completed' => $user->items()->wherePivot('status', 'Completado')->count(),
        // ];

        $stats = $this->getUserStats($user);
        
        $trendingItems = Item::query()
            ->select('items.*', DB::raw('COUNT(item_user.item_id) as additions_count'))
            ->join('item_user', 'items.id', '=', 'item_user.item_id')
            ->where('item_user.created_at', '>=', now()->subWeek())
            ->groupBy('items.id')
            ->orderByDesc('additions_count')
            ->limit(3)
            ->get();
        
        $trendingPosts = Post::withCount('likes')
        ->where('status', 'published')
        ->where('created_at', '>=', now()->subWeek())
        ->orderByDesc('likes_count')
        ->limit(3)
        ->get();
            $popularItems = [
            (object)[
                'id' => 1, 
                'title' => 'Baldur\'s Gate 3', 
                'type' => 'game', 
                'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1086940/header.jpg?t=1695246593', 
                'link' => '#'
            ],
            (object)[
                'id' => 2, 
                'title' => 'Frieren: Beyond Journey\'s End', 
                'type' => 'anime', 
                'image_url' => 'https://m.media-amazon.com/images/M/MV5BZTI4ZGMxN2UtODlkYS00MTBjLWE1YzctYzc3NDViMGI0ZmJmXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 3, 
                'title' => 'Cyberpunk 2077', 
                'type' => 'game', 
                'image_url' => 'https://i.blogs.es/b109e9/cyberpunk2077-johnny-v-rgb_en/1366_2000.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 4, 
                'title' => 'Jujutsu Kaisen', 
                'type' => 'anime', 
                'image_url' => 'https://cdn.myanimelist.net/images/anime/1171/109222.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 5, 
                'title' => 'Final Fantasy VII Rebirth', 
                'type' => 'game', 
                'image_url' => 'https://cdn11.bigcommerce.com/s-hfy8688lak/images/stencil/1280x1280/products/2593/13618/FFVII_RB_AG_US__97253.1702580872.jpg?c=1', 
                'link' => '#'
            ],
            (object)[
                'id' => 6, 
                'title' => 'Elden Ring', 
                'type' => 'game', 
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQU_cspEzfgMdenwt0VS7QHBJCOdWaVkrXyvA&s', 
                'link' => '#'
            ],
            (object)[
                'id' => 7, 
                'title' => 'Demon Slayer', 
                'type' => 'anime', 
                'image_url' => 'https://cdn.myanimelist.net/images/anime/1286/99889.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 8, 
                'title' => 'Zelda: Tears of the Kingdom', 
                'type' => 'game', 
                'image_url' => 'https://www.nintendo.com/eu/media/images/10_share_images/games_15/nintendo_switch_4/2x1_NSwitch_TloZTearsOfTheKingdom_Gamepage_image1600w.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 9, 
                'title' => 'One Piece', 
                'type' => 'anime', 
                'image_url' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 10, 
                'title' => 'Hollow Knight', 
                'type' => 'game', 
                'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/367520/header.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 11, 
                'title' => 'Attack on Titan', 
                'type' => 'anime', 
                'image_url' => 'https://cdn.myanimelist.net/images/anime/10/47347.jpg', 
                'link' => '#'
            ],
            (object)[
                'id' => 12, 
                'title' => 'God of War Ragnarök', 
                'type' => 'game', 
                'image_url' => 'https://media.revistagq.com/photos/613b43731df3ece1388a2f67/4:3/w_920,h_690,c_limit/God-of-War-Ragnarok-Featured-image.jpeg', 
                'link' => '#'
            ],
        ];
        
        return view('dashboard', [
            'activities' => $activities,
            'stats' => $stats,
            'trendingItems' => $trendingItems,
            'trendingPosts' => $trendingPosts, // 🆕
            'popularItems' => $popularItems,
        ]);
    }

    private function getUserStats($user)
    {
        
        $generalStats = DB::table('item_user')
            ->select(
                DB::raw('COUNT(*) as total_items'),
                DB::raw('SUM(CASE WHEN status = "Completado" THEN 1 ELSE 0 END) as completed'),
                //DB::raw('AVG(CASE WHEN rating IS NOT NULL THEN rating END) as avg_rating')
            )
            ->where('user_id', $user->id)
            ->first();

        $itemsByTypeAndStatus = DB::table('item_user')
            ->join('items', 'item_user.item_id', '=', 'items.id')
            ->select(
                'items.type',
                'item_user.status',
                DB::raw('COUNT(*) as count')
            )
            ->where('item_user.user_id', $user->id)
            ->groupBy('items.type', 'item_user.status')
            ->get();

        $playing = 0;
        $watching = 0;
        
        foreach ($itemsByTypeAndStatus as $stat) {
            if ($stat->type === 'game' && $stat->status === 'Jugando') {
                $playing = $stat->count;
            }
            if ($stat->type === 'anime' && $stat->status === 'Jugando') {
                $watching = $stat->count;
            }
        }

        return [
            'playing' => $playing,
            'watching' => $watching,
            'completed' => $generalStats->completed ?? 0,
            'total_items' => $generalStats->total_items ?? 0,
            'avg_rating' => round($generalStats->avg_rating ?? 0, 1),
            'completion_percentage' => $generalStats->total_items > 0 
                ? round(($generalStats->completed / $generalStats->total_items) * 100) 
                : 0
        ];
    }
}
