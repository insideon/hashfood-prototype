<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['채소', '육류', '해산물', '곡류', '양념', '유제품', '과일', '견과류'];
        $units = ['g', 'ml', '개', '큰술', '작은술'];

        return [
            'name' => fake()->word(),
            'category' => fake()->randomElement($categories),
            'unit' => fake()->randomElement($units),
            'current_price' => fake()->randomFloat(2, 100, 50000),
            'price_updated_at' => now(),
            'description' => fake()->sentence(),
            'image_url' => null,
        ];
    }
}
