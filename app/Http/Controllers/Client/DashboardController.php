<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
            'total_quotations' => $quotations->count(),
            'pending_quotations' => $quotations->where('status', 'pending')->count(),
            'total_products_viewed' => 0,
        ];

        $recentQuotations = $quotations->sortByDesc('created_at')->take(5);

        return view('client.dashboard', compact('quotations', 'stats', 'recentQuotations'));
    }
}
