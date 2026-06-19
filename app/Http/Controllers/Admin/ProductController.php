<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService
        ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Product::class);
        $query = $request->query('search');
        $categoryId = $request->query('category_id');
        $subcategoryId = $request->query('subcategory_id');
        $connectionId = $request->query('connection_id');
        $products = $this->productService->paginate(10);
        return view('admin.products.index', compact('products', 'query', 'categoryId', 'subcategoryId', 'connectionId'));
    }

    public function create(): View
    {
        $catagories = $this->categoryService->getAll();
        $this->authorize('create', Product::class);
        return view('admin.products.form',compact('catagories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $product = $this->productService->create($request->validated());

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }
       public function show(int $id): View
    {
        $this->authorize('view', Product::class);
        $product = $this->productService->findById($id);

        return view('admin.products.show', compact('product'));
    }

    public function edit(int $id): View
    {
        $this->authorize('update', Product::class);

        $product = $this->productService->findById($id);

        return view('admin.products.form', compact('product'));
    }

    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update', Product::class);

        $this->productService->update($id, $request->validated());

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', Product::class);

        $this->productService->delete($id);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->authorize('restore', Product::class);

        $this->productService->restore($id);

        return redirect()->route('admin.products.index')->with('success', 'Product restored successfully.');
    }
}
