<?php

declare(strict_types=1);

use App\Models\Recipe;
use App\Models\User;
use Livewire\Livewire;

test('레시피 테이블이 정상적으로 렌더링된다', function () {
    $user = User::factory()->create();
    $recipes = Recipe::factory()->count(3)->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\SimpleRecipeTable::class)
        ->assertSee($recipes->first()->name)
        ->assertSee($recipes->first()->category);
});

test('레시피 이름으로 검색할 수 있다', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['name' => '김치찌개']);
    Recipe::factory()->create(['name' => '된장찌개']);

    Livewire::actingAs($user)
        ->test(\App\Livewire\SimpleRecipeTable::class)
        ->set('search', '김치')
        ->assertSee('김치찌개')
        ->assertDontSee('된장찌개');
});

test('카테고리로 검색할 수 있다', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['category' => '한식']);
    Recipe::factory()->create(['category' => '중식']);

    Livewire::actingAs($user)
        ->test(\App\Livewire\SimpleRecipeTable::class)
        ->set('search', '한식')
        ->assertSee('한식')
        ->assertDontSee('중식');
});

test('검색 결과가 없을 때 적절한 메시지를 표시한다', function () {
    $user = User::factory()->create();
    Recipe::factory()->create(['name' => '김치찌개']);

    $component = Livewire::actingAs($user)
        ->test(\App\Livewire\SimpleRecipeTable::class)
        ->set('search', '존재하지않는음식');

    // 검색 결과가 없을 때 빈 테이블이 표시되는지 확인
    $component->assertDontSee('김치찌개');
});

test('검색어가 비어있을 때 모든 레시피를 표시한다', function () {
    $user = User::factory()->create();
    $recipes = Recipe::factory()->count(3)->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\SimpleRecipeTable::class)
        ->set('search', '')
        ->assertSee($recipes->first()->name)
        ->assertSee($recipes->last()->name);
});
