<?php

namespace App\Livewire;

use App\Models\Recipe;
use Livewire\Component;
use Livewire\WithPagination;

class SimpleRecipeTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 20],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $isCalculatedSort = in_array($this->sortBy, ['cooking_cost', 'savings', 'savings_percentage']);

        $recipes = Recipe::query()
            ->with('ingredients')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->when(! $isCalculatedSort, fn ($query) => $query->orderBy($this->sortBy, $this->sortDirection))
            ->paginate($this->perPage);

        // For calculated fields, sort the current page items
        if ($isCalculatedSort) {
            $items = $recipes->getCollection();

            $sorted = $items->sortBy(fn ($recipe) => match ($this->sortBy) {
                'cooking_cost' => $recipe->calculateCost(),
                'savings' => $recipe->calculateSavings(),
                'savings_percentage' => $recipe->calculateSavingsPercentage(),
                default => 0,
            }, SORT_REGULAR, $this->sortDirection === 'desc');

            $recipes->setCollection($sorted->values());
        }

        return view('livewire.simple-recipe-table', [
            'recipes' => $recipes,
        ]);
    }
}
