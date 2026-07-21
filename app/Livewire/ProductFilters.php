<?php
declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Connection;
use App\Models\Subcategory;
use App\Services\ProductService;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilters extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';
    #[Url(except: '')]
    public string $category = '';
    #[Url(except: '')]
    public string $subcategory = '';
    #[Url(except: '')]
    public string $connection = '';
    #[Url(except: '')]
    public string $machine_class = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function applyFilters(string $category = '', string $subcategory = '', string $connection = '', string $machine_class = ''): void
    {
        $this->category = $category;
        $this->subcategory = $subcategory;
        $this->connection = $connection;
        $this->machine_class = $machine_class;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category', 'subcategory', 'connection', 'machine_class']);
        $this->resetPage();
    }

    public function render(ProductService $productService): View
    {
        $filters = array_filter([
            'search' => $this->search ?: null,
            'category' => $this->resolveSlug(Category::class, $this->category ?: null),
            'subcategory' => $this->resolveSlug(Subcategory::class, $this->subcategory ?: null),
            'connection' => $this->resolveSlug(Connection::class, $this->connection ?: null),
            'machine_class' => $this->machine_class ?: null,
            'status' => '1',
        ]);

        $hasFilters = $this->search !== '' || $this->category !== '' || $this->subcategory !== '' || $this->connection !== '' || $this->machine_class !== '';
        $products = $hasFilters ? $productService->paginate(28, $filters) : collect();
        $categories = Category::query()->orderBy('name')->pluck('name', 'slug')->toArray();
        $subcategories = Subcategory::query()
            ->with('category:id,name,slug')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category_id']);
        $connections = Connection::query()->orderBy('name')->pluck('name', 'slug')->toArray();
        return view('livewire.product-filters', compact('products', 'categories', 'subcategories', 'connections', 'hasFilters'));
    }

    private function resolveSlug(string $modelClass, ?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)) {
            return $value;
        }

        $record = $modelClass::where('slug', $value)->first();

        return $record?->getKey();
    }
}
