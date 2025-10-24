<?php

use App\Models\User;
use App\Models\UserPreference;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();

    // 온보딩을 완료하기 위해 UserPreference 생성
    UserPreference::create([
        'user_id' => $user->id,
        'favorite_recipes' => [1, 2, 3], // 최소 3개
        'budget_limit' => 100000,
        'preferred_quality' => 'normal',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertStatus(200);
});

test('users without onboarding are redirected to onboarding', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('onboarding'));
});
