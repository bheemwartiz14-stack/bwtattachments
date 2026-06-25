<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected SubcategoryService $subcategoryService,
        protected ConnectionService $connectionService,
    ) {}

    public function index(Request $request): View
    {
        $filters = array_filter([
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'subcategory' => $request->input('subcategory'),
            'connection' => $request->input('connection'),
        ]);

        $products = $this->productService->paginate(12, $filters);
        $categories = $this->categoryService->getAll();
        $subcategories = $this->subcategoryService->getAllWithCategory();
        $connections = $this->connectionService->getAll();

        return view('products.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

    public function show(string $id): View
    {
        $product = $this->productService->findById($id);
        $product->load('category', 'subcategory', 'connection', 'media');

        return view('products.show', compact('product'));
    }
}
