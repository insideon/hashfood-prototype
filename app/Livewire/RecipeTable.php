<?php

namespace App\Livewire;

use App\Models\Recipe;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class RecipeTable extends DataTableComponent
{
    protected $model = Recipe::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('name', 'asc')
            ->setPerPageAccepted([10, 20, 25, 50, 100])
            ->setPerPage(20)
            ->setPerPageVisibilityEnabled()
            ->setSearchEnabled()
            ->setSearchPlaceholder('음식명으로 검색...')
            ->setSearchDebounce(300)
            ->setEmptyMessage('레시피가 없습니다.')
            ->setTableWrapperAttributes([
                'class' => 'bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden'
            ])
            ->setTableAttributes([
                'class' => 'w-full'
            ])
            ->setTheadAttributes([
                'class' => 'bg-gray-50 dark:bg-zinc-900'
            ])
            ->setTbodyAttributes([
                'class' => 'divide-y divide-gray-200 dark:divide-zinc-700'
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make('음식명', 'name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return '<div class="font-medium text-gray-900 dark:text-white">' . e($value) . '</div>';
                })
                ->html(),

            Column::make('카테고리', 'category')
                ->sortable()
                ->format(function ($value) {
                    $colors = [
                        '한식' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                        '중식' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400',
                        '일식' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                        '양식' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                        '디저트' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400',
                        '간식' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                    ];
                    
                    $color = $colors[$value] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                    
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . e($value) . '</span>';
                })
                ->html(),

            Column::make('난이도', 'difficulty')
                ->sortable()
                ->format(function ($value) {
                    $difficulties = [
                        'easy' => ['text' => '쉬움', 'color' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'],
                        'medium' => ['text' => '보통', 'color' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400'],
                        'hard' => ['text' => '어려움', 'color' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'],
                    ];
                    
                    $difficulty = $difficulties[$value] ?? ['text' => $value, 'color' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'];
                    
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $difficulty['color'] . '">' . e($difficulty['text']) . '</span>';
                })
                ->html(),

            Column::make('조리시간', 'cooking_time')
                ->sortable()
                ->format(function ($value) {
                    return '<div class="text-sm text-gray-600 dark:text-gray-400">' . $value . '분</div>';
                })
                ->html(),

            Column::make('집밥 원가', 'id')
                ->format(function ($value, $row) {
                    $cost = $row->calculateCost();
                    return '<div class="text-sm font-medium text-gray-900 dark:text-white">₩' . number_format($cost) . '</div>';
                })
                ->html(),

            Column::make('배달비', 'delivery_price')
                ->sortable()
                ->format(function ($value) {
                    return '<div class="text-sm font-medium text-gray-900 dark:text-white">₩' . number_format($value) . '</div>';
                })
                ->html(),

            Column::make('절약금액', 'id')
                ->format(function ($value, $row) {
                    $savings = $row->calculateSavings();
                    $color = $savings > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                    return '<div class="text-sm font-medium ' . $color . '">₩' . number_format($savings) . '</div>';
                })
                ->html(),

            Column::make('절약률', 'id')
                ->format(function ($value, $row) {
                    $percentage = $row->calculateSavingsPercentage();
                    $color = $percentage > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                    return '<div class="text-sm font-medium ' . $color . '">' . number_format($percentage, 0) . '%</div>';
                })
                ->html(),

            Column::make('액션', 'id')
                ->format(function ($value, $row) {
                    return '<a href="' . route('recipes.show', $row) . '" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">보기</a>';
                })
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            TextFilter::make('음식명')
                ->config([
                    'placeholder' => '음식명으로 검색...',
                ])
                ->filter(function($builder, string $value) {
                    return $builder->where('name', 'like', '%' . $value . '%');
                }),

            SelectFilter::make('카테고리')
                ->options([
                    '' => '전체',
                    '한식' => '한식',
                    '중식' => '중식',
                    '일식' => '일식',
                    '양식' => '양식',
                    '디저트' => '디저트',
                    '간식' => '간식',
                ])
                ->filter(function($builder, string $value) {
                    return $builder->where('category', $value);
                }),

            SelectFilter::make('난이도')
                ->options([
                    '' => '전체',
                    'easy' => '쉬움',
                    'medium' => '보통',
                    'hard' => '어려움',
                ])
                ->filter(function($builder, string $value) {
                    return $builder->where('difficulty', $value);
                }),
        ];
    }
}
