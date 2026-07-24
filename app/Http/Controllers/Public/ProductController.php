<?php
declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Connection;
use App\Models\Product;
use App\Models\Subcategory;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
            'category' => $this->resolveSlug(Category::class, $request->input('category')),
            'subcategory' => $this->resolveSlug(Subcategory::class, $request->input('subcategory')),
            'connection' => $this->resolveSlug(Connection::class, $request->input('connection')),
            'status' => "1",
        ]);
        $products = $this->productService->paginate(12, $filters);
        $categories = Category::query()->orderBy('name')->pluck('name', 'slug')->toArray();
        $subcategories = Subcategory::query()
            ->with('category:id,name,slug')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category_id']);
        $connections = Connection::query()->orderBy('name')->pluck('name', 'slug')->toArray();
        return view('pages.public.products.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

    private function resolveSlug(string $modelClass, ?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)) {
            return $value;
        }

        $record = $modelClass::where('slug', $value)->first();

        return $record?->getKey();
    }

    public function show(Product $product): View
    {
        $product->load('category', 'subcategory', 'connection', 'media');

        return view('pages.public.products.show', compact('product'));
    }

    public function downloadPdf(Product $product): BinaryFileResponse
    {
        $media = $product->getFirstMedia('pdfs');

        if (!$media) {
            abort(404);
        }

        $user = auth()->user();

        if (!$user || !($user->hasRole('Wholesaler') || $user->hasRole('Admin'))) {
            abort(403, 'Unauthorized. Only Wholesales can download PDF drawings.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
