<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService
    ) {}

    public function index(): View
    {
        $products = $this->productService->paginate(12);
        $categories = $this->categoryService->getAll();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(int $id): View
    {
        $product = $this->productService->findById($id);
        $product->load('category', 'subcategory', 'connection', 'images');

        return view('products.show', compact('product'));
    }
}
