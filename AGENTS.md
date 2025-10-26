# Repository Guidelines

## Project Structure & Module Organization
Laravel services live in `app/`, with HTTP layer code grouped under `app/Http` and Livewire Volt components stored beside their Blade templates in `resources/views`. Front-end assets are split between `resources/js` (Flux stores, Alpine helpers) and `resources/css` for Tailwind layers; shared localization strings reside in `lang/`. Routes are defined in `routes/web.php` for browser flows and `routes/api.php` for JSON endpoints. Database schema changes belong in `database/migrations`, while seed data and factories live in `database/seeders` and `database/factories`. Tests are organized under `tests/Feature` and `tests/Unit`, mirroring domain boundaries.

## Build, Test, and Development Commands
Run `composer install && npm install` during setup. Use `composer dev` to boot the full stack (Laravel server, queue worker, Pail logs, Vite) in one process; stop it with `Ctrl+C`. For single tasks, `php artisan serve`, `php artisan migrate --seed`, and `npm run dev` are the usual trio. Ship-ready assets come from `npm run build`. Execute `composer test` (aliases `php artisan test`) before pushing to ensure Pest suites pass.

## Coding Style & Naming Conventions
Follow PSR-12 formatting for PHP classes and keep namespace folders aligned with class names (e.g., `App/Services/Food/FoodService.php`). Blade and Volt files use kebab-case, such as `resources/views/recipes/index.blade.php`; accompanying Livewire Volt classes stay in the same directory. JavaScript modules in `resources/js` use camelCase exports and should avoid default exports for clarity. Run `./vendor/bin/pint` to auto-format PHP, and rely on Tailwind utility conventions in markup instead of bespoke CSS when possible.

## Testing Guidelines
Pest is the primary test runner (see `tests/Pest.php`). Place HTTP flow checks in `tests/Feature/*Test.php` and pure domain logic in `tests/Unit/*Test.php`; name files after the scenario, e.g., `CreateRecipeTest.php`. Prefer Pest expectations for readability (`expect($response->status())->toBe(200)`). Use the `RefreshDatabase` trait for migration safety and add factories for new models before asserting persisted state. Aim to cover new endpoints and Livewire actions with at least one happy-path and one failure-path assertion.

## Commit & Pull Request Guidelines
Commits follow a Conventional Commit prefix such as `feat:`, `fix:`, or `chore:` (see recent history: `fix: remove login/register input placeholder`). Keep messages in the imperative mood and scope each commit to one logical change. For pull requests, include a concise summary, reference the related issue or PRD section, list setup steps if migrations or new env keys are required, and attach screenshots or screen recordings for UI-facing updates. Ensure CI (tests and Pint) runs clean locally before requesting review.
