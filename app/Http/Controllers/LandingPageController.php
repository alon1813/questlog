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
        $recentActivities = collect(); 

        if (Auth::check()) {
            $user = Auth::user();
            
        } else {
            
            $recentActivities = Activity::with('user', 'subject') 
                                    ->whereIn('type', ['created_post', 'updated_list_item']) 
                                    ->latest() 
                                    ->limit(3) 
                                    ->get();
        }

        $popularItems = [
            (object)['id' => 1, 'title' => 'Baldur\'s Gate 3', 'type' => 'game', 'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1086940/header.jpg?t=1695246593', 'link' => '#'],
            (object)['id' => 2, 'title' => 'Frieren: Beyond Journey\'s End', 'type' => 'anime', 'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT9XensSLQi9eS5vQZJOqdoR9hnTp9ed9va1Ii9SeovpjtXTY4PW24Lf_UcZjcJEgjV1n4ApuvqWTFnNiqYWufJjGSznhMJsX4i13KiLg&s=10', 'link' => '#'],
            (object)['id' => 3, 'title' => 'Cyberpunk 2077', 'type' => 'game', 'image_url' => 'https://i.blogs.es/b109e9/cyberpunk2077-johnny-v-rgb_en/1366_2000.jpg', 'link' => '#'],
            (object)['id' => 4, 'title' => 'Jujutsu Kaisen S2', 'type' => 'anime', 'image_url' => 'https://m.media-amazon.com/images/S/pv-target-images/574a947b4f0b8e1f6007bc9f80df638fb1d8df5b7e833f9223295a09b8b080e0.jpg', 'link' => '#'],
            (object)['id' => 5, 'title' => 'Final Fantasy VII Rebirth', 'type' => 'game', 'image_url' => 'https://img.asmedia.epimg.net/resizer/v2/UDSICW5LJRDRTA6X4PG3FH3T64.jpg?auth=da022ed3eef539abbd48865720b51e8cebe3ef04ac33b7f86737f07a18b81a57&width=1200&height=1200&smart=true', 'link' => '#'],
        ];

        return view('landing', compact('user', 'recentActivities', 'popularItems'));
    }
}