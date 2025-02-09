<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryServer>
 */
class CategoryServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();

        // Выбираем сервер ТОЛЬКО из той же игры
        $server = Server::where('game_id', $category->game_id)
            ->inRandomOrder()
            ->first();

        return [
            'category_id' => $category->id,
            'server_id' => $server->id,
        ];
    }
}
