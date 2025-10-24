<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['한식', '중식', '일식', '양식', '분식', '찌개/탕', '구이', '볶음'];
        $difficulties = ['easy', 'medium', 'hard'];

        return [
            'name' => fake()->word().' 요리',
            'description' => fake()->sentence(),
            'cooking_time' => fake()->numberBetween(10, 120),
            'difficulty' => fake()->randomElement($difficulties),
            'servings' => fake()->numberBetween(1, 4),
            'category' => fake()->randomElement($categories),
            'image_url' => null,
            'delivery_price' => fake()->randomFloat(2, 8000, 30000),
            'instructions' => fake()->paragraph(),
        ];
    }
}
