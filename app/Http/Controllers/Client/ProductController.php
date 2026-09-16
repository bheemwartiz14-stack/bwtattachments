<?php
declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
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

    public function index(Request $request): View
    {

        $categories = $this->categoryService->getAll();
        $subcategories = $this->subcategoryService->getAll();
        $connections = $this->connectionService->getAll();
        $perPage = (int) $request->query('per_page', 100);
        if (! in_array($perPage, [100, 200, 300, 400, 500], true)) {
            $perPage = 100;
        }
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
        $products = $this->productService->paginate($perPage, $filter);
        return view('pages.private.client.products.index', compact('products', 'categories', 'subcategories', 'connections', 'perPage'));
    }

    public function show(string $id): View
    {
        $product = $this->productService->findById($id);
        return view('pages.private.client.products.show', compact('product'));
    }
}
