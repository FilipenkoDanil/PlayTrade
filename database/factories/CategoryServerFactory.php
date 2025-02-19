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
        $server = Server::inRandomOrder()->first();

        $category = Category::where('game_id', $server->game_id)->inRandomOrder()->first();

        return [
            'category_id' => $category->id,
            'server_id' => $server->id,
        ];
    }
}
