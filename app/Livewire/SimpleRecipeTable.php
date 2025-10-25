<?php

namespace App\Livewire;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
            ->setSearchEnabled()
            ->setSearchPlaceholder('음식명, 카테고리로 검색...')
            ->setSearchIcon('heroicon-m-magnifying-glass')
            ->setSearchIconAttributes([
                'class' => 'h-5 w-5 text-gray-400 dark:text-gray-500',
                'style' => 'position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);',
            ])
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
                // 액션 컬럼은 중앙 정렬
                if ($column->getTitle() === '액션') {
                    return [
                        'class' => 'px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors',
                        'default' => false,
                    ];
                }

                // 다른 컬럼들은 왼쪽 정렬
                return [
                    'class' => 'px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors',
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
                ->searchable()
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

            Column::make('배달 가격', 'delivery_price')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm text-zinc-600 dark:text-zinc-300">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약 금액', 'calculated_savings')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-green-600 dark:text-green-400">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약률', 'calculated_savings_percentage')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-blue-600 dark:text-blue-400">'.number_format($value, 0).'%</div>')
                ->html(),

            Column::make('액션', 'id')
                ->format(fn ($value) => '<div class="text-center"><a href="'.route('recipes.show', ['recipeId' => $value]).'" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition-colors duration-200">보기</a></div>')
                ->html(),
        ];
    }

    /**
     * 데이터베이스 쿼리 빌더
     *
     * 계산된 컬럼들을 포함한 레시피 데이터를 조회합니다.
     * 성능 최적화를 위해 캐싱과 서브쿼리 재사용을 적용합니다.
     */
    public function builder(): Builder
    {
        // 캐시 키 생성 (재료 가격이 변경되면 캐시 무효화)
        $cacheKey = 'recipe_calculations_'.$this->getIngredientsLastUpdated();

        // 캐시에서 계산된 데이터 가져오기
        $cachedCalculations = Cache::remember($cacheKey, 3600, function () {
            return $this->calculateAllRecipeCosts();
        });

        // 기본 레시피 데이터 조회
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
            ]);

        // 캐시된 계산 결과를 서브쿼리로 추가
        if (! empty($cachedCalculations)) {
            $recipeIds = array_keys($cachedCalculations);
            $caseStatements = $this->buildCaseStatements($cachedCalculations);

            $query->whereIn('recipes.id', $recipeIds)
                ->selectRaw("({$caseStatements['cooking_cost']}) as calculated_cooking_cost")
                ->selectRaw("({$caseStatements['savings']}) as calculated_savings")
                ->selectRaw("({$caseStatements['savings_percentage']}) as calculated_savings_percentage");
        } else {
            // 캐시가 비어있을 때는 기본값으로 설정
            $query->selectRaw('0 as calculated_cooking_cost')
                ->selectRaw('0 as calculated_savings')
                ->selectRaw('0 as calculated_savings_percentage');
        }

        return $query;
    }

    /**
     * 모든 레시피의 비용을 계산하여 캐시에 저장
     */
    private function calculateAllRecipeCosts(): array
    {
        $calculations = [];

        // 모든 레시피와 재료 관계를 한 번에 로드
        $recipes = Recipe::with(['ingredients' => function ($query) {
            $query->select('ingredients.id', 'ingredients.current_price')
                ->withPivot('quantity');
        }])->get();

        foreach ($recipes as $recipe) {
            $cookingCost = $recipe->ingredients->sum(function ($ingredient) {
                return $ingredient->pivot->quantity * $ingredient->current_price;
            });

            $savings = $recipe->delivery_price - $cookingCost;
            $savingsPercentage = $recipe->delivery_price > 0
                ? ($savings / $recipe->delivery_price) * 100
                : 0;

            $calculations[$recipe->id] = [
                'cooking_cost' => $cookingCost,
                'savings' => $savings,
                'savings_percentage' => $savingsPercentage,
            ];
        }

        return $calculations;
    }

    /**
     * 재료 테이블의 마지막 업데이트 시간을 가져와서 캐시 키에 사용
     */
    private function getIngredientsLastUpdated(): string
    {
        $lastUpdated = Cache::remember('ingredients_last_updated', 3600, function () {
            return DB::table('ingredients')->max('updated_at') ?? 'never';
        });

        return md5($lastUpdated);
    }

    /**
     * 캐시된 계산 결과를 SQL CASE 문으로 변환
     */
    private function buildCaseStatements(array $calculations): array
    {
        $cookingCostCases = [];
        $savingsCases = [];
        $savingsPercentageCases = [];

        foreach ($calculations as $recipeId => $data) {
            $cookingCostCases[] = "WHEN recipes.id = {$recipeId} THEN {$data['cooking_cost']}";
            $savingsCases[] = "WHEN recipes.id = {$recipeId} THEN {$data['savings']}";
            $savingsPercentageCases[] = "WHEN recipes.id = {$recipeId} THEN {$data['savings_percentage']}";
        }

        return [
            'cooking_cost' => 'CASE '.implode(' ', $cookingCostCases).' ELSE 0 END',
            'savings' => 'CASE '.implode(' ', $savingsCases).' ELSE 0 END',
            'savings_percentage' => 'CASE '.implode(' ', $savingsPercentageCases).' ELSE 0 END',
        ];
    }

    /**
     * 정렬이 적용된 쿼리 빌더 반환
     *
     * 라이브와이어 테이블 라이브러리의 기본 정렬 로직을 오버라이드하여
     * 계산된 컬럼들도 정렬할 수 있도록 합니다.
     * 라이브와이어 테이블이 자동으로 페이징을 처리하므로 limit을 제거합니다.
     */
    public function getBuilder(): Builder
    {
        $builder = $this->builder();

        // 검색어가 있으면 적용
        if ($this->getSearch()) {
            $searchTerm = $this->getSearch();
            $builder->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('category', 'like', '%'.$searchTerm.'%');
            });
        }

        // 정렬 적용
        foreach ($this->getSorts() as $field => $direction) {
            $builder->orderBy($field, $direction);
        }

        // 라이브와이어 테이블이 자동으로 페이징 처리하므로 limit 제거
        return $builder;
    }
}
