<?php
declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\LaravelPdf\Facades\Pdf;

class HomeController extends Controller
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
        return view('public.home.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

    public function testPdf()
    {
        $quotation = \App\Models\Quotation::with(['items.product', 'reseller.userMeta'])->latest()->first();

        if (!$quotation) {
            return response('No quotations found. Create one first.');
        }

        return Pdf::view('pdf.quotations', compact('quotation'))
            ->format('A4')
            ->driver('dompdf');
    }
}
