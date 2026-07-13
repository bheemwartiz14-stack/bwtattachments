<?php

namespace App\Http\Controllers\Reseller;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RetailerProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected SubcategoryService $subcategoryService,
        protected ConnectionService $connectionService
    ) {}

    public function index(Request $request): View
    {
        $categories = $this->categoryService->getAll();
        $subcategories = $this->subcategoryService->getAll();
        $connections = $this->connectionService->getAll();
        $per_page = (int) $request->query('per_page', 15);
        $filter = array_merge(
            $request->only([
                'search',
                'category',
                'subcategory',
                'connection',
                'machine_class',
                'status',
            ])
        );
        $products = $this->productService->paginate($per_page, $filter);
        return view('pages.private.reseller.products.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

        public function show(string $id): View
    {
        $product = $this->productService->findById($id);
        $userPrice = $product->productPrices->where('user_id', auth()->id())->first();
        $displayPrice = $userPrice ? $userPrice->final_price : $product->ddp_price;
        $product->load(['category', 'subcategory', 'connection', 'productPrices' => fn ($q) => $q->where('user_id', auth()->id())]);
        return view('pages.private.reseller.products.show', compact('product', 'userPrice', 'displayPrice'));
    }
}
