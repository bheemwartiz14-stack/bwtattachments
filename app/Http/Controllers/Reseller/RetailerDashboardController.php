<?php
declare(strict_types=1);
namespace App\Http\Controllers\Reseller;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\OrderServices;
use App\Services\QuotationService;
use Illuminate\View\View;

class RetailerDashboardController extends Controller
{
     public function __construct(
        protected QuotationService $quotationService, #DEPECTATED CODE
        protected OrderServices $orderServices,
    ) {}
   public function index(): View
    {
        $userId = auth()->id();
        $user = auth()->user();
        $orders = $this->orderServices->findByUser($userId);
        $quotations = $this->quotationService->findByUser($userId);
        $companyName = $user->userMeta?->metadata['client_name'] ?? $user->userMeta?->metadata['retailer_client_name']  ?? $user->name;
        $lastLogin = $user->created_at;
        $stats = [
            'total_products' => Product::count(),
            'total_orders'   => $orders->total(),
            'quotations'     => $quotations->count(),
        ];
        return view('pages.private.reseller.dashboard', compact(
                'stats',
                'companyName',
                'lastLogin',
                'user'
            ));
    }
    //
}
