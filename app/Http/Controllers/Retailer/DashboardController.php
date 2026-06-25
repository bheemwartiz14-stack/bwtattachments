<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Quotation;
use App\Services\QuotationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected QuotationService $quotationService) {}

    public function index(): View
    {
        $userId = auth()->id();
        $quotations = $this->quotationService->findByUser($userId);

        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_quotations' => $quotations->count(),
            'pending_quotations' => $quotations->where('status', 'pending')->count(),
        ];

        $recentQuotations = $quotations->sortByDesc('created_at')->take(5);
        $recentProducts = Product::latest()->take(5)->get();

        return view('retailer.dashboard', compact('stats', 'recentQuotations', 'recentProducts'));
    }
}
