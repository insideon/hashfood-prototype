<?php

namespace App\Livewire;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * 레시피 테이블 컴포넌트
 *
 * 계산된 컬럼(집밥 원가, 절약금액, 절약률)을 포함한 레시피 목록을 표시합니다.
 * 모든 컬럼이 정렬 가능하며, 통계 섹션과 일치하는 zinc 색상 테마를 사용합니다.
 */
class SimpleRecipeTable extends DataTableComponent
{
    protected $model = Recipe::class;

    protected $listeners = ['refreshComponent' => '$refresh'];

    /**
     * 테이블 설정
     * - 기본 정렬: 절약률 높은순 (내림차순)
     * - 페이지당 20개 항목 표시
     * - 통계 섹션과 일치하는 zinc 색상 테마 적용
     */
    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('calculated_savings_percentage', 'desc')
            ->setSearchDisabled()
            ->setPerPageVisibilityDisabled()
            ->setPerPageAccepted([10])
            ->setPerPage(10)
            ->setSortingPillsDisabled()
            ->setColumnSelectDisabled()
            ->setDisplayPaginationDetails(false)
            ->setPaginationWrapperAttributes([
                'class' => 'flex justify-center mt-4',
                'default' => false,
            ])
            ->setTableWrapperAttributes([
                'class' => 'border border-zinc-200 dark:!border-zinc-700 rounded-2xl shadow-sm overflow-hidden',
                'default' => false,
            ])
            ->setTableAttributes([
                'class' => 'min-w-full bg-white dark:bg-zinc-800 text-gray-900 dark:text-white',
                'default' => false,
            ])
            ->setTheadAttributes([
                'class' => 'border-b border-zinc-200 dark:border-zinc-700',
                'default' => false,
            ])
            ->setTbodyAttributes([
                'class' => 'divide-y divide-zinc-200 dark:divide-zinc-700',
                'default' => false,
            ])
            ->setThAttributes(function ($column) {
                return [
                    'class' => 'px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors',
                    'default' => false,
                ];
            })
            ->setTdAttributes(function ($column, $row) {
                return [
                    'class' => 'px-6 py-4 text-sm text-zinc-900 dark:text-zinc-200',
                    'default' => false,
                ];
            })
            ->setTrAttributes(function ($row) {
                return [
                    'class' => 'hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors',
                    'default' => false,
                ];
            })
            ->setEmptyMessage('레시피를 찾을 수 없습니다');
    }

    /**
     * 테이블 컬럼 정의
     *
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make('음식명', 'name')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">'.$value.'</div>')
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
                    $color = $colors[$value] ?? 'text-zinc-600 dark:text-zinc-400';

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

                    return '<div class="text-sm text-zinc-600 dark:text-zinc-300">'.$text.'</div>';
                })
                ->html(),

            Column::make('조리시간', 'cooking_time')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm text-zinc-600 dark:text-zinc-300">'.$value.'분</div>')
                ->html(),

            Column::make('집밥 원가', 'calculated_cooking_cost')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('배달비', 'delivery_price')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm text-zinc-600 dark:text-zinc-300">₩'.number_format($value).'</div>')
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

    /**
     * 데이터베이스 쿼리 빌더
     *
     * 계산된 컬럼들을 포함한 레시피 데이터를 조회합니다.
     * 성능 최적화를 위해 서브쿼리를 재사용합니다.
     *
     * @return Builder
     */
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
     * 정렬이 적용된 쿼리 빌더 반환
     *
     * 라이브와이어 테이블 라이브러리의 기본 정렬 로직을 오버라이드하여
     * 계산된 컬럼들도 정렬할 수 있도록 합니다.
     *
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        $builder = $this->builder();

        // 정렬 적용
        foreach ($this->getSorts() as $field => $direction) {
            $builder->orderBy($field, $direction);
        }

        // 페이지당 항목 수 강제 설정
        $builder->limit(20);

        return $builder;
    }
}
