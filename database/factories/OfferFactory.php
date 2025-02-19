<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factory>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::query()->inRandomOrder()->first();
        $server = $category->servers()->inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(5),
            'amount' => rand(1, 100),
            'price' => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->paragraph(),
            'is_active' => rand(0, 1),
            'category_id' => $category->id,
            'seller_id' => User::query()->inRandomOrder()->value('id'),
            'server_id' => $server ? $server->id : null,
        ];
    }
}
