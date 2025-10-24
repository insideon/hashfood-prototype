<?php

namespace App\Livewire;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SimpleRecipeTable extends DataTableComponent
{
    protected $model = Recipe::class;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('name', 'asc')
            ->setSearchDisabled()
            ->setPerPageVisibilityDisabled()
            ->setPerPageAccepted([10, 20, 50, 100])
            ->setPerPage(20)
            ->setTableAttributes([
                'class' => 'min-w-full',
            ])
            ->setTheadAttributes([
                'class' => 'border-b border-gray-200 dark:border-zinc-700',
            ])
            ->setTbodyAttributes([
                'class' => 'divide-y divide-gray-100 dark:divide-zinc-700',
            ])
            ->setThAttributes(function ($column) {
                return [
                    'class' => 'px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors',
                ];
            })
            ->setTdAttributes(function ($column, $row) {
                return [
                    'class' => 'px-6 py-4 text-sm',
                ];
            })
            ->setTrAttributes(function ($row) {
                return [
                    'class' => 'hover:bg-gray-50 dark:hover:bg-zinc-700/30 transition-colors',
                ];
            })
            ->setEmptyMessage('레시피를 찾을 수 없습니다');
    }

    public function columns(): array
    {
        return [
            Column::make('음식명', 'name')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-gray-900 dark:text-white">'.$value.'</div>')
                ->html(),

            Column::make('카테고리', 'category')
                ->sortable()
                ->format(function ($value) {
                    $colors = [
                        '한식' => 'text-red-600 dark:text-red-400',
                        '중식' => 'text-orange-600 dark:text-orange-400',
                        '일식' => 'text-blue-600 dark:text-blue-400',
                        '양식' => 'text-green-600 dark:text-green-400',
                    ];
                    $color = $colors[$value] ?? 'text-gray-600 dark:text-gray-400';

                    return '<div class="text-sm font-medium '.$color.'">'.$value.'</div>';
                })
                ->html(),

            Column::make('난이도', 'difficulty')
                ->sortable()
                ->format(function ($value) {
                    $text = match ($value) {
                        'easy' => '쉬움',
                        'medium' => '보통',
                        'hard' => '어려움',
                        default => $value,
                    };

                    return '<div class="text-sm text-gray-600 dark:text-gray-400">'.$text.'</div>';
                })
                ->html(),

            Column::make('조리시간', 'cooking_time')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm text-gray-600 dark:text-gray-400">'.$value.'분</div>')
                ->html(),

            Column::make('집밥 원가', 'calculated_cooking_cost')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-gray-900 dark:text-white">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('배달비', 'delivery_price')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm text-gray-600 dark:text-gray-400">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약금액', 'calculated_savings')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-green-600 dark:text-green-400">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약률', 'calculated_savings_percentage')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-blue-600 dark:text-blue-400">'.number_format($value, 0).'%</div>')
                ->html(),

            Column::make('액션', 'id')
                ->format(fn ($value) => '<a href="'.route('recipes.show', ['recipeId' => $value]).'" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition-colors duration-200">보기</a>')
                ->html(),
        ];
    }

    public function builder(): Builder
    {
        // 성능 최적화: 서브쿼리를 한 번만 실행하고 재사용
        $cookingCostSubquery = '(
            SELECT COALESCE(SUM(ri.quantity * i.current_price), 0)
            FROM recipe_ingredients ri
            JOIN ingredients i ON i.id = ri.ingredient_id
            WHERE ri.recipe_id = recipes.id
        )';

        $query = Recipe::query()
            ->select([
                'recipes.id',
                'recipes.name',
                'recipes.description',
                'recipes.cooking_time',
                'recipes.difficulty',
                'recipes.servings',
                'recipes.category',
                'recipes.image_url',
                'recipes.delivery_price',
                'recipes.instructions',
                'recipes.created_at',
                'recipes.updated_at',
            ])
            ->selectRaw("{$cookingCostSubquery} as calculated_cooking_cost")
            ->selectRaw("(delivery_price - {$cookingCostSubquery}) as calculated_savings")
            ->selectRaw("(
                CASE
                    WHEN delivery_price > 0 THEN
                        ((delivery_price - {$cookingCostSubquery}) / delivery_price) * 100
                    ELSE 0
                END
            ) as calculated_savings_percentage");

        return $query;
    }


    /**
     * 라이브와이어 테이블 라이브러리가 자동으로 컬럼을 추가하는 것을 방지
     */
    public function getBuilder(): Builder
    {
        $builder = $this->builder();

        // 정렬 적용
        foreach ($this->getSorts() as $field => $direction) {
            $builder->orderBy($field, $direction);
        }

        return $builder;
    }

}
