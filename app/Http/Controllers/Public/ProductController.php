<?php
declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            'status' => "1",
        ]);
        $products = $this->productService->paginate(12, $filters);
        $categories = $this->categoryService->getAll();
        $subcategories = $this->subcategoryService->getAllWithCategory();
        $connections = $this->connectionService->getAll();

        return view('pages.public.products.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

    public function show(string $id): View
    {
        $product = $this->productService->findById($id);
        $product->load('category', 'subcategory', 'connection', 'media');

        return view('pages.public.products.show', compact('product'));
    }

    public function downloadPdf(string $id): Response
    {
        $product = $this->productService->findById($id);
        $media = $product->getFirstMedia('pdfs');

        if (!$media) {
            abort(404);
        }

        $user = auth()->user();

        if (!$user || !($user->hasRole('Wholesale Client') || $user->hasRole('Super Admin'))) {
            abort(403, 'Unauthorized. Only wholesale clients can download PDF drawings.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
