<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\OrderServices;
use App\Services\QuotationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected QuotationService $quotationService, #DEPECTATED CODE
        protected OrderServices $orderServices,
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $userId = $user->id;
        $orders = $this->orderServices->findByUser($userId);
        $quotations = $this->quotationService->findByUser($userId);
        $companyName = $user->userMeta?->metadata['wholesale_company_name']  ?? $user->name;
        $lastLogin = $user->created_at;
        $stats = [
            'total_products' => Product::count(),
            'total_orders'   => $orders->total(),
            'quotations'     => $quotations->count(),
        ];
        return view(
            'pages.private.client.dashboard',
            compact('stats', 'companyName', 'lastLogin', 'user')
        );
    }
}
