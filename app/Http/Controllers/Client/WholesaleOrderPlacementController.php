<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserProductService;
use Illuminate\View\View;

class WholesaleOrderPlacementController extends Controller
{
    public function index(): View
    {
        $orders = collect([
            ['number' => 'ORD-20260831-001', 'reference' => 'PO-10024', 'date' => '31 Aug 2026', 'status' => 'Draft', 'total' => '€12,450.00'],
            ['number' => 'ORD-20260830-002', 'reference' => 'PO-10023', 'date' => '30 Aug 2026', 'status' => 'Sent', 'total' => '€8,720.00'],
            ['number' => 'ORD-20260828-003', 'reference' => 'PO-10018', 'date' => '28 Aug 2026', 'status' => 'Completed', 'total' => '€21,300.00'],
        ]);
        return view('pages.private.client.orders.index', compact('orders'));
    }

    public function create(UserProductService $userProductService): View
    {
        $user = auth()->user();
        $company = $user->userMeta?->metadata ?? [];
        $admin = User::role('Admin')->orderBy('name')->first(['id', 'name', 'email', 'phone']);
        $cartIds = $userProductService->getQuotationProductIds($user);
        return view('pages.private.client.orders.create', [
            'company' => $company,
            'admin' => $admin,
            'cartIds' => $cartIds,
            'orderNumber' => 'ORD-'.now()->format('Ymd-His'),
            'orderDate' => now(),
        ]);
    }
}
