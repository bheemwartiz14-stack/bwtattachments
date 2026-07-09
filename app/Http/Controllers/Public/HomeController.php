<?php
declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Connection;
use App\Models\Subcategory;
use App\Services\CategoryService;
use App\Services\ConnectionService;
use App\Services\ProductService;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
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
            'category' => $this->resolveSlug(Category::class, $request->input('category')),
            'subcategory' => $this->resolveSlug(Subcategory::class, $request->input('subcategory')),
            'connection' => $this->resolveSlug(Connection::class, $request->input('connection')),
            'machine_class' => $request->input('machine_class'),
            'status' => "1",
        ]);
        $products = $this->productService->paginate(12, $filters);
        $categories = Category::query()->orderBy('name')->pluck('name', 'slug')->toArray();
        $subcategories = Subcategory::query()
            ->with('category:id,name,slug')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category_id']);
        $connections = Connection::query()->orderBy('name')->pluck('name', 'slug')->toArray();
        return view('pages.public.index', compact('products', 'categories', 'subcategories', 'connections'));
    }

    public function testEmail(): \Illuminate\Http\RedirectResponse
    {
        $email = "bheem.wartiz14@gmail.com";
        $name = config('app.name');
        try {
            Mail::raw('This is a test email from ' . $name . '. If you received this, email is working correctly.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email from ' . config('app.name'));
            });
        } catch (\Throwable $e) {
            return redirect('/')->with('error', 'Email sending failed: ' . $e->getMessage());
        }

        return redirect('/')->with('success', 'Test email sent to ' . $email);
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
