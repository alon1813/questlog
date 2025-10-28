<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Asocia a un usuario
            'post_id' => Post::factory(), // Asocia a un post
            'body' => fake()->paragraph(), // Genera texto falso
            'status' => 'visible',        // Estado por defecto
            
        ];
    }
}
