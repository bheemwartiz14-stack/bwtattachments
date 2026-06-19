<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\QuotationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected QuotationService $quotationService) {}

    public function index(): View
    {
        $quotations = $this->quotationService->findByUser(auth()->id());

        return view('client.dashboard', compact('quotations'));
    }
}
