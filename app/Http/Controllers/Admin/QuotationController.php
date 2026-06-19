<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Services\QuotationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function __construct(protected QuotationService $quotationService) {}

    public function index(): View
    {
        $this->authorize('viewAny', Quotation::class);

        $quotations = $this->quotationService->paginate(10);

        return view('admin.quotations.index', compact('quotations'));
    }

    public function show(int $id): View
    {
        $this->authorize('view', Quotation::class);

        $quotation = $this->quotationService->findById($id);
        $quotation->load('items.product', 'user.company');

        return view('admin.quotations.show', compact('quotation'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', Quotation::class);

        $this->quotationService->delete($id);

        return redirect()->route('admin.quotations.index')->with('success', 'Quotation deleted successfully.');
    }
}
