<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index(Request $request){
        $user = $request->user();
        $activities = Activity::with('user', 'subject')->latest()->take(20)->get();

        $stats = [
            'playing' => $user->items()->where('type', 'game')->wherePivot('status', 'Jugando')->count(),
            'watching' => $user->items()->where('type', 'anime')->wherePivot('status', 'Jugando')->count(), // 'Jugando' es 'Viendo'
            'completed' => $user->items()->wherePivot('status', 'Completado')->count(),
        ];
        
        $trendingItems = Item::query()
            ->select('items.*', DB::raw('COUNT(item_user.item_id) as additions_count'))
            ->join('item_user', 'items.id', '=', 'item_user.item_id')
            ->where('item_user.created_at', '>=', now()->subWeek())
            ->groupBy('items.id')
            ->orderByDesc('additions_count')
            ->limit(3)
            ->get();
        
        $popularItems = [
            (object)['id' => 1, 'title' => 'Baldur\'s Gate 3', 'type' => 'game', 'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1086940/header.jpg?t=1695246593', 'link' => '#'],
            (object)['id' => 2, 'title' => 'Frieren: Beyond Journey\'s End', 'type' => 'anime', 'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT9XensSLQi9eS5vQZJOqdoR9hnTp9ed9va1Ii9SeovpjtXTY4PW24Lf_UcZjcJEgjV1n4ApuvqWTFnNiqYWufJjGSznhMJsX4i13KiLg&s=10', 'link' => '#'],
            (object)['id' => 3, 'title' => 'Cyberpunk 2077', 'type' => 'game', 'image_url' => 'https://i.blogs.es/b109e9/cyberpunk2077-johnny-v-rgb_en/1366_2000.jpg', 'link' => '#'],
            (object)['id' => 4, 'title' => 'Jujutsu Kaisen S2', 'type' => 'anime', 'image_url' => 'https://m.media-amazon.com/images/S/pv-target-images/574a947b4f0b8e1f6007bc9f80df638fb1d8df5b7e833f9223295a09b8b080e0.jpg', 'link' => '#'],
            (object)['id' => 5, 'title' => 'Final Fantasy VII Rebirth', 'type' => 'game', 'image_url' => 'https://img.asmedia.epimg.net/resizer/v2/UDSICW5LJRDRTA6X4PG3FH3T64.jpg?auth=da022ed3eef539abbd48865720b51e8cebe3ef04ac33b7f86737f07a18b81a57&width=1200&height=1200&smart=true', 'link' => '#'],
            (object)['id' => 5, 'title' => 'Final Fantasy VII Rebirth', 'type' => 'game', 'image_url' => 'https://img.asmedia.epimg.net/resizer/v2/UDSICW5LJRDRTA6X4PG3FH3T64.jpg?auth=da022ed3eef539abbd48865720b51e8cebe3ef04ac33b7f86737f07a18b81a57&width=1200&height=1200&smart=true', 'link' => '#'],
            (object)['id' => 5, 'title' => 'Final Fantasy VII Rebirth', 'type' => 'game', 'image_url' => 'https://img.asmedia.epimg.net/resizer/v2/UDSICW5LJRDRTA6X4PG3FH3T64.jpg?auth=da022ed3eef539abbd48865720b51e8cebe3ef04ac33b7f86737f07a18b81a57&width=1200&height=1200&smart=true', 'link' => '#'],
        ];

        return view('dashboard', [
            'activities' => $activities,
            'stats' => $stats,
            'trendingItems' => $trendingItems,
            'popularItems' => $popularItems,
        ]);
    }
}
