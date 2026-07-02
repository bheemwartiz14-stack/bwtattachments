<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;

class RetailerProductController extends Controller
{
     public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected SubcategoryService $subcategoryService,
        protected ConnectionService $connectionService
    ) {}
     public function index(): View
    {
        $categories = $this->categoryService->getAll();
        $subcategories = $this->subcategoryService->getAll();
        $connections = $this->connectionService->getAll();
        $parent_id = auth()->user()->parent_id;
        $filter = [
            'category' => request()->query('category'),
            'subcategory' => request()->query('subcategory'),
            'connection' => request()->query('connection'),
            'search' => request()->query('search'),
            'status' => request()->query('status'),
        ];
        $perPage = (int) request()->query('per_page', 10);
        $parent_id = "";
        $products = $this->productService->paginateActiveProductsForUser($filter, $perPage, $parent_id);
        return view('retailer.products.index', compact('products', 'categories', 'subcategories', 'connections'));
    }


    public function show(string $id): View
    {
        $product = $this->productService->findById($id);
        $userPrice = $product->productPrices->where('user_id', auth()->id())->first();
        $displayPrice = $userPrice ? $userPrice->final_price : $product->ddp_price;
        $product->load(['category', 'subcategory', 'connection', 'productPrices' => fn ($q) => $q->where('user_id', auth()->id())]);
        return view('retailer.products.show', compact('product', 'userPrice', 'displayPrice'));
    }
}
