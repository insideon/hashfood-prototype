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
    protected $queryString = [];

    // 상수 정의
    private const DEFAULT_SORT_FIELD = 'calculated_savings_percentage';
    private const DEFAULT_SORT_DIRECTION = 'desc';
    private const ITEMS_PER_PAGE = 5;
    private const SEARCH_PLACEHOLDER = '오늘은 어떤 음식을 드실건가요?';

    /**
     * 컴포넌트 마운트 시 상태 관리
     */
    public function mount(): void
    {
        $this->resetToDefaultState();
    }

    /**
     * 검색어 변경 시 상태 관리
     */
    public function updatedSearch(array|string|null $value): void
    {
        if (empty($value)) {
            $this->resetToDefaultState();
        }
    }

    /**
     * 기본 상태로 리셋
     */
    private function resetToDefaultState(): void
    {
        $this->resetPage();
        $this->setSort(self::DEFAULT_SORT_FIELD, self::DEFAULT_SORT_DIRECTION);
    }

    /**
     * 테이블 설정
     */
    public function configure(): void
    {
        $this->setBasicConfiguration()
            ->setSearchConfiguration()
            ->setPaginationConfiguration()
            ->setQueryStringConfiguration()
            ->setTableStyling();
    }

    /**
     * 기본 설정
     */
    private function setBasicConfiguration(): self
    {
        return $this->setPrimaryKey('id')
            ->setDefaultSort(self::DEFAULT_SORT_FIELD, self::DEFAULT_SORT_DIRECTION)
            ->setSortingPillsDisabled()
            ->setColumnSelectDisabled()
            ->setDisplayPaginationDetails(false);
    }

    /**
     * 검색 설정
     */
    private function setSearchConfiguration(): self
    {
        return $this->setSearchEnabled()
            ->setSearchPlaceholder(self::SEARCH_PLACEHOLDER)
            ->setSearchIcon('heroicon-m-magnifying-glass')
            ->setSearchIconAttributes([
                'class' => 'h-5 w-5 text-gray-400 dark:text-gray-500',
                'style' => 'position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);',
            ]);
    }

    /**
     * 페이지네이션 설정
     */
    private function setPaginationConfiguration(): self
    {
        return $this->setPerPageVisibilityDisabled()
            ->setPerPageAccepted([self::ITEMS_PER_PAGE])
            ->setPerPage(self::ITEMS_PER_PAGE)
            ->setPaginationWrapperAttributes([
                'class' => 'flex justify-center mt-4',
                'default' => false,
            ]);
    }

    /**
     * 쿼리스트링 설정
     */
    private function setQueryStringConfiguration(): self
    {
        return $this->setQueryStringDisabled()
            ->setQueryStringForSearchDisabled()
            ->setQueryStringForSortDisabled()
            ->setQueryStringForFilterDisabled();
    }

    /**
     * 테이블 스타일링 설정
     */
    private function setTableStyling(): self
    {
        return $this->setTableWrapperAttributes([
                'class' => 'border border-zinc-200 dark:!border-zinc-700 rounded-2xl shadow-sm overflow-x-auto',
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
                'class' => 'bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700',
                'default' => false,
            ])
            ->setThAttributes(function ($column) {
                $baseClass = 'px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors';

                if ($column->getTitle() === '액션') {
                    return [
                        'class' => $baseClass . ' text-center',
                        'default' => false,
                    ];
                }

                return [
                    'class' => $baseClass . ' text-left',
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
            ->setEmptyMessage('레시피를 찾을 수 없습니다.')
            ->setToolBarAttributes([
                'class' => 'flex flex-col md:mb-4 md:p-0',
                'default-styling' => false,
            ]);
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
                ->format(fn ($value) => '<div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 overflow-hidden text-ellipsis whitespace-nowrap">'.$value.'</div>')
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

                    return '<div class="text-sm font-medium '.$color.' overflow-hidden text-ellipsis whitespace-nowrap">'.$value.'</div>';
                })
                ->html(),

            Column::make('집밥 원가', 'calculated_cooking_cost')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 overflow-hidden text-ellipsis whitespace-nowrap">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('배달 가격', 'delivery_price')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm text-zinc-600 dark:text-zinc-300 overflow-hidden text-ellipsis whitespace-nowrap">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약 금액', 'calculated_savings')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-green-600 dark:text-green-400 overflow-hidden text-ellipsis whitespace-nowrap">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약률', 'calculated_savings_percentage')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-blue-600 dark:text-blue-400 overflow-hidden text-ellipsis whitespace-nowrap">'.number_format($value, 0).'%</div>')
                ->html(),

            Column::make('액션', 'id')
                ->format(fn ($value) => '<div class="text-center overflow-hidden text-ellipsis whitespace-nowrap"><a href="'.route('recipes.show', ['recipeId' => $value]).'" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition-colors duration-200 whitespace-nowrap">보기</a></div>')
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
        $cacheKey = 'recipe_calculations_' . $this->getIngredientsLastUpdated();

        // 계산된 값들을 캐시에서 가져오거나 계산
        $calculations = Cache::remember($cacheKey, 3600, function () {
            return $this->calculateRecipeCosts();
        });

        // CASE 문으로 계산된 컬럼 추가
        $caseStatements = $this->buildCaseStatements($calculations);

        return Recipe::query()
            ->select([
                'recipes.*',
                DB::raw($caseStatements['cooking_cost'] . ' as calculated_cooking_cost'),
                DB::raw($caseStatements['savings'] . ' as calculated_savings'),
                DB::raw($caseStatements['savings_percentage'] . ' as calculated_savings_percentage'),
            ])
            ->with('ingredients');
    }

    /**
     * 정렬이 적용된 쿼리 빌더 반환
     *
     * 라이브와이어 테이블 라이브러리의 기본 정렬 로직을 오버라이드하여
     * 계산된 컬럼에 대한 정렬을 지원합니다.
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

    /**
     * 모든 레시피의 비용 계산
     */
    private function calculateRecipeCosts(): array
    {
        $recipes = Recipe::with('ingredients')->get();
        $calculations = [];

        foreach ($recipes as $recipe) {
            $cookingCost = $recipe->calculateCost();
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
}