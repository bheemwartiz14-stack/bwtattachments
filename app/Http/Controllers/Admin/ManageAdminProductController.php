<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageAdminProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected ConnectionService $connectionService,
        protected SubcategoryService $subcategoryService,
        protected UserService $userService,
    ) {}

    private function formData(): array
    {
        return [
            'categories' => $this->categoryService->getAll(),
            'connectionTypes' => $this->connectionService->getAll(),

        ];
    }

    public function index(Request $request): View
    {
        return view('admin.products.index', [
            'products' => $this->productService->paginate(
                (int) $request->query('per_page', 15),
                $request->only(['search', 'category', 'subcategory', 'connection', 'status'])
            ),
            'categories' => $this->categoryService->getAll(),
            'subcategories' => $this->subcategoryService->getAll(),
            'connections' => $this->connectionService->getAll(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => null] + $this->formData());
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(string $id): View
    {
        return view('admin.products.show', ['product' => $this->productService->findById($id)]);
    }

    public function edit(string $id): View
    {
        return view('admin.products.form', ['product' => $this->productService->findById($id)] + $this->formData());
    }

    public function update(UpdateProductRequest $request, string $id): RedirectResponse
    {
        $this->productService->update($id, $request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $deleted = $this->productService->delete($id);

        if (!$deleted) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Cannot delete product: it has linked quotation items.');
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
