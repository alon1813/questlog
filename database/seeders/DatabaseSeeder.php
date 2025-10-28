<?php

namespace Database\Seeders;

// --- Modelos Necesarios ---
use App\Models\User;
use App\Models\Comment;
use App\Models\Item;       // <-- Añadido
use App\Models\Post;
use App\Models\Product;    // <-- Añadido

// --- Otros ---
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Añadido para contraseñas

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. Usuarios Específicos ---
        $adminUser = User::factory()->admin()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // <-- Añadido Hash::make
        ]);

        $testUser = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // <-- Añadido Hash::make
        ]);

        // --- 2. Usuarios Aleatorios Adicionales ---
        $otherUsers = User::factory(8)->create();
        // Colección con TODOS los usuarios para usarla después
        $allUsers = $otherUsers->push($testUser)->push($adminUser); 

        // --- 3. Items de Prueba (Juegos/Animes) ---
        // Creamos algunos manualmente para tener IDs conocidos
        $itemWitcher = Item::factory()->create(['api_id' => 3498, 'type' => 'game', 'title' => 'The Witcher 3: Wild Hunt', 'episodes' => null, 'cover_image_url' => 'https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcS2Dg_0Ihf2BZ0zcvagXeqAqkY7Kh3G6dPHHPQM-k8EQJz9Kglm-5vqf-qIjUBmrbBQCQIUeNRbev1uD4eVHH8hEWgtC8q5ane6sdCz-1zRSw']);
        $itemZelda = Item::factory()->create(['api_id' => 58866, 'type' => 'game', 'title' => 'Zelda: Tears of the Kingdom', 'episodes' => null, 'cover_image_url' => 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRUjpdsKkLTXBrmxZt6JS6KVH9n6RJTd9Ra4uKbX48ms8E7XQnZn5HdorJj6pGdpInOWBnn5rs7rmqgqL5eJD7MDWr1exZAl8BwSyjQW-xE']);
        $itemShingeki = Item::factory()->create(['api_id' => 16498, 'type' => 'anime', 'title' => 'Shingeki no Kyojin', 'episodes' => 88, 'cover_image_url' => 'https://cdn.myanimelist.net/images/anime/10/47347.jpg']);
        $itemJujutsu = Item::factory()->create(['api_id' => 40748, 'type' => 'anime', 'title' => 'Jujutsu Kaisen', 'episodes' => 24, 'cover_image_url' => 'https://cdn.myanimelist.net/images/anime/1171/109222.jpg']);
        $allItems = collect([$itemWitcher, $itemZelda, $itemShingeki, $itemJujutsu]);

        // --- 4. Posts y Comentarios ---
        // Crea 15 posts, cada uno con 0-5 comentarios, usando usuarios aleatorios
        Post::factory(15)->recycle($allUsers)->create()->each(function ($post) use ($allUsers) {
            Comment::factory(rand(0, 5))->recycle($allUsers)->create(['post_id' => $post->id]);
        });
        // Asegúrate de que tu usuario de prueba tenga al menos un post
        Post::factory()->recycle($testUser)->create(['title' => 'Post de Prueba para Comentarios']);

        // --- 5. Colecciones de Items (item_user) ---
        foreach ($allUsers as $user) {
            // Cada usuario añade 1-3 items aleatorios a su colección
            $itemsToAdd = $allItems->random(rand(1, $allItems->count() < 3 ? $allItems->count() : 3))->unique(); // unique() para no añadir el mismo dos veces
            foreach ($itemsToAdd as $item) {
                $status = ['Pendiente', 'Jugando', 'Completado'][rand(0, 2)];
                $score = ($status === 'Completado' || $status === 'Jugando') ? rand(5, 10) : null;
                $review = ($status === 'Completado' && rand(0, 1)) ? fake()->paragraph() : null;
                $episodesWatched = 0;
                if ($item->type === 'anime' && $status !== 'Pendiente' && $item->episodes > 0) {
                    $episodesWatched = ($status === 'Completado') ? $item->episodes : rand(1, $item->episodes -1);
                }

                // Attach con datos pivote
                $user->items()->attach($item->id, [
                    'status' => $status,
                    'score' => $score,
                    'review' => $review,
                    'episodes_watched' => $episodesWatched,
                    'created_at' => now()->subDays(rand(1, 30)), // Fechas aleatorias
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]);
            }
        }

        // --- 6. Productos (Llama al ProductSeeder) ---
        $this->call(ProductSeeder::class);
        $allProducts = Product::all(); // Obtenemos los productos creados por el seeder

        // --- 7. Listas de Deseos (product_user) ---
        foreach ($allUsers as $user) {
            // Cada usuario añade 0-2 productos aleatorios a su lista
            if ($allProducts->count() > 0) {
                $productsToAdd = $allProducts->random(rand(0, $allProducts->count() < 2 ? $allProducts->count() : 2))->unique();
                foreach ($productsToAdd as $product) {
                    $user->wishlistProducts()->attach($product->id, ['quantity' => rand(1, 3)]);
                }
            }
        }

        // --- 8. Seguidores (follower_user) ---
        foreach ($allUsers as $user) {
            // Cada usuario sigue a 0-3 otros usuarios aleatorios (asegurándose de no seguirse a sí mismo)
            if ($allUsers->count() > 1) { // Solo si hay más de 1 usuario
                $usersToFollow = $allUsers->where('id', '!=', $user->id)->random(rand(0, $allUsers->count() <= 3 ? $allUsers->count() -1 : 3))->unique();
                foreach ($usersToFollow as $userToFollow) {
                    $user->following()->attach($userToFollow->id);
                }
            }
        }

        // Puedes añadir aquí llamadas a otros seeders si los creas en el futuro
    }
}