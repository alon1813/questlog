<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Comment;
use App\Models\Item;       
use App\Models\Post;
use App\Models\Product;    
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $adminUser = User::factory()->admin()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), 
        ]);

        $testUser = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), 
        ]);

        
        $otherUsers = User::factory(8)->create();
        $allUsers = $otherUsers->push($testUser)->push($adminUser); 

        $itemWitcher = Item::factory()->create(['api_id' => 3498, 'type' => 'game', 'title' => 'The Witcher 3: Wild Hunt', 'episodes' => null, 'cover_image_url' => 'https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcS2Dg_0Ihf2BZ0zcvagXeqAqkY7Kh3G6dPHHPQM-k8EQJz9Kglm-5vqf-qIjUBmrbBQCQIUeNRbev1uD4eVHH8hEWgtC8q5ane6sdCz-1zRSw']);
        $itemZelda = Item::factory()->create(['api_id' => 58866, 'type' => 'game', 'title' => 'Zelda: Tears of the Kingdom', 'episodes' => null, 'cover_image_url' => 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRUjpdsKkLTXBrmxZt6JS6KVH9n6RJTd9Ra4uKbX48ms8E7XQnZn5HdorJj6pGdpInOWBnn5rs7rmqgqL5eJD7MDWr1exZAl8BwSyjQW-xE']);
        $itemShingeki = Item::factory()->create(['api_id' => 16498, 'type' => 'anime', 'title' => 'Shingeki no Kyojin', 'episodes' => 88, 'cover_image_url' => 'https://cdn.myanimelist.net/images/anime/10/47347.jpg']);
        $itemJujutsu = Item::factory()->create(['api_id' => 40748, 'type' => 'anime', 'title' => 'Jujutsu Kaisen', 'episodes' => 24, 'cover_image_url' => 'https://cdn.myanimelist.net/images/anime/1171/109222.jpg']);
        $allItems = collect([$itemWitcher, $itemZelda, $itemShingeki, $itemJujutsu]);

        Post::factory(15)->recycle($allUsers)->create()->each(function ($post) use ($allUsers) {
            Comment::factory(rand(0, 5))->recycle($allUsers)->create(['post_id' => $post->id]);
        });
        Post::factory()->recycle($testUser)->create(['title' => 'Post de Prueba para Comentarios']);


        foreach ($allUsers as $user) {
            $itemsToAdd = $allItems->random(rand(1, $allItems->count() < 3 ? $allItems->count() : 3))->unique(); 
            foreach ($itemsToAdd as $item) {
                $status = ['Pendiente', 'Jugando', 'Completado'][rand(0, 2)];
                $score = ($status === 'Completado' || $status === 'Jugando') ? rand(5, 10) : null;
                $review = ($status === 'Completado' && rand(0, 1)) ? fake()->paragraph() : null;
                $episodesWatched = 0;
                if ($item->type === 'anime' && $status !== 'Pendiente' && $item->episodes > 0) {
                    $episodesWatched = ($status === 'Completado') ? $item->episodes : rand(1, $item->episodes -1);
                }

                
                $user->items()->attach($item->id, [
                    'status' => $status,
                    'score' => $score,
                    'review' => $review,
                    'episodes_watched' => $episodesWatched,
                    'created_at' => now()->subDays(rand(1, 30)), 
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]);
            }
        }

        
        $this->call(ProductSeeder::class);
        $allProducts = Product::all(); 

        
        foreach ($allUsers as $user) {
            if ($allProducts->count() > 0) {
                $productsToAdd = $allProducts->random(rand(0, $allProducts->count() < 2 ? $allProducts->count() : 2))->unique();
                foreach ($productsToAdd as $product) {
                    $user->wishlistProducts()->attach($product->id, ['quantity' => rand(1, 3)]);
                }
            }
        }

        foreach ($allUsers as $user) {
            
            if ($allUsers->count() > 1) { 
                $usersToFollow = $allUsers->where('id', '!=', $user->id)->random(rand(0, $allUsers->count() <= 3 ? $allUsers->count() -1 : 3))->unique();
                foreach ($usersToFollow as $userToFollow) {
                    $user->following()->attach($userToFollow->id);
                }
            }
        }

        
    }
}