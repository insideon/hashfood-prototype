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
    protected $queryString = [];

    // 상수 정의
    private const DEFAULT_SORT_FIELD = 'savings_percentage';
    private const DEFAULT_SORT_DIRECTION = 'desc';
    private const ITEMS_PER_PAGE = 10;
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
                    ->setDisplayPaginationDetails(false)
                    ->setLoadingPlaceholderDisabled();
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
            ])
            ->setPaginationEnabled()
            ->setPaginationMethod('standard');
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
                $baseClass = 'px-3 py-2 md:px-6 md:py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors whitespace-nowrap';

                if ($column->getTitle() === '집밥 원가' || $column->getTitle() === '배달 가격' || $column->getTitle() === '절약률') {
                    return [
                        'class' => $baseClass . ' text-left overflow-visible relative',
                        'default' => false,
                    ];
                }

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
                    'class' => 'px-3 py-2 md:px-6 md:py-4 text-sm text-zinc-900 dark:text-zinc-200 whitespace-nowrap',
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

            Column::make('배달 가격', 'delivery_price')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm text-zinc-600 dark:text-zinc-300 overflow-hidden text-ellipsis whitespace-nowrap">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('집밥 원가', 'cooking_cost')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 overflow-hidden text-ellipsis whitespace-nowrap">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약 금액', 'savings')
                ->sortable()
                ->format(fn ($value) => '<div class="text-sm font-semibold text-green-600 dark:text-green-400 overflow-hidden text-ellipsis whitespace-nowrap">₩'.number_format($value).'</div>')
                ->html(),

            Column::make('절약률', 'savings_percentage')
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
     */
    public function builder(): Builder
    {
        return Recipe::query();
    }

}