<?php

namespace App\Livewire;

use App\Models\Recipe;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

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
                'class' => 'bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700',
            ])
            ->setTableAttributes([
                'class' => 'min-w-full divide-y divide-gray-200 dark:divide-gray-700',
            ])
            ->setTheadAttributes([
                'class' => 'bg-gray-50 dark:bg-gray-700',
            ])
            ->setTbodyAttributes([
                'class' => 'bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700',
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make('음식명', 'name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return '<div class="text-base font-semibold text-gray-900 dark:text-white">'.e($value).'</div>';
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
                    ];

                    $color = $colors[$value] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$color.'">'.e($value).'</span>';
                })
                ->html(),

            Column::make('난이도', 'difficulty')
                ->sortable()
                ->format(function ($value, $row) {
                    return '<div class="text-sm text-gray-600 dark:text-gray-400">'.$row->difficulty_korean.'</div>';
                })
                ->html(),

            Column::make('조리시간', 'cooking_time')
                ->sortable()
                ->format(function ($value) {
                    return '<div class="text-sm text-gray-600 dark:text-gray-400">'.$value.'분</div>';
                })
                ->html(),

            Column::make('집밥 원가', 'id')
                ->format(function ($value, $row) {
                    $cost = $row->calculateCost();
                    return '<div class="text-sm font-medium text-gray-900 dark:text-white">₩'.number_format($cost).'</div>';
                })
                ->html(),

            Column::make('배달비', 'delivery_price')
                ->sortable()
                ->format(function ($value) {
                    return '<div class="text-sm text-gray-600 dark:text-gray-400">₩'.number_format($value).'</div>';
                })
                ->html(),

            Column::make('절약금액', 'id')
                ->format(function ($value, $row) {
                    $savings = $row->calculateSavings();
                    return '<div class="text-sm font-semibold text-green-600 dark:text-green-400">₩'.number_format($savings).'</div>';
                })
                ->html(),

            Column::make('절약률', 'id')
                ->format(function ($value, $row) {
                    $percentage = $row->calculateSavingsPercentage();
                    return '<div class="text-sm font-semibold text-blue-600 dark:text-blue-400">'.number_format($percentage, 0).'%</div>';
                })
                ->html(),

            Column::make('액션', 'id')
                ->format(function ($value, $row) {
                    return '<a href="'.route('recipes.show', $row).'" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">보기</a>';
                })
                ->html(),
        ];
    }
}