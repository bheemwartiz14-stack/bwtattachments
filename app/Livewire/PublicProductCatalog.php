<?php

namespace App\Livewire;

use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PublicProductCatalog extends Component
{
    use WithPagination;

    #[Url(as: 'search', history: true)]
    public string $search = '';

    #[Url(as: 'category', history: true)]
    public array $selectedCategories = [];

    #[Url(as: 'subcategory', history: true)]
    public array $selectedSubcategories = [];

    #[Url(as: 'connection', history: true)]
    public array $selectedConnections = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategories(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedSubcategories(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedConnections(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategories = [];
        $this->selectedSubcategories = [];
        $this->selectedConnections = [];
        $this->resetPage();
    }

    public function removeFilter(string $type, string $value): void
    {
        match ($type) {
            'category' => $this->selectedCategories = array_values(array_diff($this->selectedCategories, [$value])),
            'subcategory' => $this->selectedSubcategories = array_values(array_diff($this->selectedSubcategories, [$value])),
            'connection' => $this->selectedConnections = array_values(array_diff($this->selectedConnections, [$value])),
            default => null,
        };
        $this->resetPage();
    }

    public function getProductsProperty()
    {
        $filters = array_filter([
            'search' => $this->search ?: null,
            'category' => $this->selectedCategories ?: null,
            'subcategory' => $this->selectedSubcategories ?: null,
            'connection' => $this->selectedConnections ?: null,
        ]);

        return app(ProductService::class)->paginate(12, $filters);
    }

    public function getAllCategoriesProperty(): array
    {
        return app(CategoryService::class)->getAll();
    }

    public function getAllSubcategoriesProperty(): array
    {
        return app(SubcategoryService::class)->getAll();
    }

    public function getAllConnectionsProperty(): array
    {
        return app(ConnectionService::class)->getAll();
    }

    public function render(): View
    {
        return view('livewire.public-product-catalog')
            ->layout('components.layouts.public');
    }
}
