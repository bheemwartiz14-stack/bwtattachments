<?php
declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected SubcategoryService $subcategoryService,
        protected ConnectionService $connectionService
    ) {}

    public function index(): View
    {
        $products = $this->productService->getAllWithClientProducts(12);
        $categories = $this->categoryService->getAll();
        $subcategories = $this->subcategoryService->getAll();
        $connections = $this->connectionService->getAll();
        return view('client.products.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

    public function show(string $id): View
    {
        $product = $this->productService->findById($id);
        $product->load('category', 'subcategory', 'connection');
        return view('client.products.show', compact('product'));
    }

    public function search(Request $request): JsonResponse
    {
        $term = $request->input('q', '');
        $products = $this->productService->search($term);

        return response()->json($products->map(fn ($p) => [
            'id' => $p->id,
            'product_code' => $p->product_code,
            'product_description' => $p->product_description,
            'ddp_price' => $p->ddp_price,
            'image' => $p->getFirstMediaUrl('images', 'thumb'),
            'category' => $p->category?->name,
        ]));
    }
}
