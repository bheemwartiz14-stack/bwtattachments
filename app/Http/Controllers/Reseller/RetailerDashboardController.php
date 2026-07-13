<?php
declare(strict_types=1);
namespace App\Http\Controllers\Reseller;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Quotation;
use App\Services\QuotationService;
use Illuminate\View\View;

class RetailerDashboardController extends Controller
{
      public function __construct(protected QuotationService $quotationService) {}

   public function index(): View
    {
        $userId = auth()->id();
        $user = auth()->user();
        $quotations = $this->quotationService->findByUser($userId);

        $companyName = $user->userMeta?->metadata['client_name']
            ?? $user->userMeta?->metadata['retailer_client_name']
            ?? $user->name;
        $lastLogin = $user->created_at;

        $stats = [
            'total_products' => Product::count(),
            'draft_quotations' => $quotations->where('status', 'draft')->count(),
            'sent_quotations' => $quotations->where('status', 'sent')->count(),
            'downloads' => $quotations->whereNotNull('pdf_file')->count(),
        ];

        $recentQuotations = $quotations->sortByDesc('created_at')->take(5)->load('items');
        $recentProducts = Product::where('status', true)
            ->with(['productPrices' => fn ($q) => $q->where('user_id', $userId)])
            ->latest()->take(5)->get();

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
                $recentProducts->map(fn ($p) => [
                    'type' => 'New Product',
                    'message' => $p->product_title,
                    'time' => $p->created_at->diffForHumans(),
                    'icon' => 'cube',
                ])
            )
            ->sortByDesc(fn ($n) => $n['time'])
            ->take(5);

        return view('pages.private.reseller.dashboard', compact(
            'stats', 'recentQuotations', 'recentProducts', 'notifications',
            'companyName', 'lastLogin', 'user'
        ));
    }
    //
}
