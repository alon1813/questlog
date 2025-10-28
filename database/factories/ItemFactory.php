<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Para generar strings aleatorios

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $type = ['game', 'anime'][rand(0, 1)]; // Elige aleatoriamente entre juego y anime
        return [
            'api_id' => fake()->unique()->randomNumber(5), // Un ID de API falso y único
            'type' => $type,
            'title' => fake()->words(rand(2, 5), true), // Título falso
            'cover_image_url' => null,
            'episodes' => ($type === 'anime') ? rand(1, 100) : null, // Episodios solo para anime
            'synopsis' => fake()->paragraph(), // Sinopsis falsa
        ];
    }
}