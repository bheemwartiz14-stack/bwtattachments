<?php
declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
        $user = auth()->user();
        $quotations = $this->quotationService->findByUser($userId);

        $companyName = $user->userMeta?->metadata['wholesale_company_name'] ?? $user->name;
        $lastLogin = $user->created_at;

        $stats = [
            'total_products' => Product::count(),
            'draft_quotations' => $quotations->where('status', 'draft')->count(),
            'sent_quotations' => $quotations->where('status', 'sent')->count(),
            'downloads' => $quotations->whereNotNull('pdf_file')->count(),
        ];

        $recentQuotations = $quotations->sortByDesc('created_at')->take(5)->load('items');

        $notifications = collect()
            ->merge(
                $recentQuotations->map(fn ($q) => [
                    'type' => 'New PDF',
                    'message' => "Quotation {$q->quotation_number} generated",
                    'time' => $q->created_at->diffForHumans(),
                    'icon' => 'document-text',
                ])
            )
            ->merge(
                $quotations->sortByDesc('updated_at')->take(3)->map(fn ($q) => [
                    'type' => 'Price Update',
                    'message' => "Quotation {$q->quotation_number} updated",
                    'time' => $q->updated_at->diffForHumans(),
                    'icon' => 'currency-dollar',
                ])
            )
            ->sortByDesc(fn ($n) => $n['time'])
            ->take(5);

        return view('client.dashboard', compact(
            'stats', 'recentQuotations', 'notifications',
            'companyName', 'lastLogin', 'user'
        ));
    }
}
