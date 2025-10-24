<?php

use App\Models\Ingredient;
use App\Models\Recipe;

test('recipe can calculate total cost based on ingredients', function () {
    $recipe = Recipe::factory()->create([
        'servings' => 2,
    ]);

    $ingredient1 = Ingredient::factory()->create([
        'name' => '김치',
        'current_price' => 200,
    ]);

    $ingredient2 = Ingredient::factory()->create([
        'name' => '돼지고기',
        'current_price' => 150,
    ]);

    $recipe->ingredients()->attach($ingredient1->id, ['quantity' => 100]);
    $recipe->ingredients()->attach($ingredient2->id, ['quantity' => 200]);

    $totalCost = $recipe->calculateCost();

    expect($totalCost)->toBe(50000.0);
});

test('recipe can calculate cost per serving', function () {
    $recipe = Recipe::factory()->create([
        'servings' => 2,
    ]);

    $ingredient = Ingredient::factory()->create([
        'current_price' => 100,
    ]);

    $recipe->ingredients()->attach($ingredient->id, ['quantity' => 200]);

    $costPerServing = $recipe->calculateCostPerServing();

    expect($costPerServing)->toBe(10000.0);
});

test('recipe can calculate cost for different serving sizes', function () {
    $recipe = Recipe::factory()->create([
        'servings' => 2,
    ]);

    $ingredient = Ingredient::factory()->create([
        'current_price' => 100,
    ]);

    $recipe->ingredients()->attach($ingredient->id, ['quantity' => 100]);

    $costFor4Servings = $recipe->calculateCost(4);

    expect($costFor4Servings)->toBe(20000.0);
});

test('recipe can calculate savings compared to delivery', function () {
    $recipe = Recipe::factory()->create([
        'servings' => 2,
        'delivery_price' => 18000,
    ]);

    $ingredient = Ingredient::factory()->create([
        'current_price' => 100,
    ]);

    $recipe->ingredients()->attach($ingredient->id, ['quantity' => 45]);

    $savings = $recipe->calculateSavings();

    expect($savings)->toBe(13500.0);
});

test('recipe can calculate savings percentage', function () {
    $recipe = Recipe::factory()->create([
        'servings' => 2,
        'delivery_price' => 20000,
    ]);

    $ingredient = Ingredient::factory()->create([
        'current_price' => 100,
    ]);

    $recipe->ingredients()->attach($ingredient->id, ['quantity' => 50]);

    $savingsPercentage = $recipe->calculateSavingsPercentage();

    expect($savingsPercentage)->toBe(75.0);
});

test('ingredient can update price and create history', function () {
    $ingredient = Ingredient::factory()->create([
        'current_price' => 100,
    ]);

    $oldPrice = $ingredient->current_price;

    $ingredient->updatePrice(150, 'test_source');

    $ingredient->refresh();

    expect((float) $ingredient->current_price)->toBe(150.0);
    expect($ingredient->priceHistories)->toHaveCount(1);
    expect((float) $ingredient->priceHistories->first()->price)->toBe((float) $oldPrice);
    expect($ingredient->priceHistories->first()->source)->toBe('test_source');
});
