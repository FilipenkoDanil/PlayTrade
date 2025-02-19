<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->text(),
            'game_id' => Game::query()->inRandomOrder()->value('id'),
            'unit_id' => Unit::query()->inRandomOrder()->value('id'),
        ];
    }
}
