<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
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
        $products = $this->productService->paginate(12);
        $categories = $this->categoryService->getAll();
        $subcategories = $this->subcategoryService->getAll();
        $connections = $this->connectionService->getAll();

        return view('client.products.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

    public function show(int $id): View
    {
        $product = $this->productService->findById($id);
        $product->load('category', 'subcategory', 'connection', 'images');

        return view('client.products.show', compact('product'));
    }
}
