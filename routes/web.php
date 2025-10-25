<?php

use App\Http\Controllers\RssFeedController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// SEO routes
Route::get('sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('rss.xml', RssFeedController::class)->name('rss');

// Landing page
Volt::route('/', 'landing')->name('home');

// Legal pages
Volt::route('terms', 'pages.terms')->name('terms');
Volt::route('privacy', 'pages.privacy')->name('privacy');

// Recipe routes
Volt::route('recipes', 'recipes.index')->name('recipes.index');
Volt::route('recipes/{recipeId}', 'recipes.show')->name('recipes.show');

// Price tracking route
Volt::route('price-tracking', 'price-tracking')->name('price-tracking');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'onboarding'])
    ->name('dashboard');

Route::get('onboarding', function () {
    return view('onboarding');
})->middleware(['auth', 'verified'])->name('onboarding');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
