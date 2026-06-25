<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\Quotation;
use App\Services\QuotationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotationController extends Controller
{
    public function __construct(protected QuotationService $quotationService) {}

    public function index(): View
    {
        $quotations = $this->quotationService->findByUser(auth()->id());

        return view('client.quotations.index', compact('quotations'));
    }

    public function create(): View
    {
        return view('client.quotations.create');
    }

    public function store(StoreQuotationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $quotation = $this->quotationService->create($data);

        foreach ($data['items'] as $item) {
            $this->quotationService->addItem(
                $quotation->id,
                $item['product_id'],
                $item['price'] ?? 0,
                $item['quantity']
            );
        }

        return redirect()->route('client.quotations.show', $quotation->id)
            ->with('success', 'Quotation created successfully.');
    }

    public function show(int $id): View
    {
        $quotation = $this->quotationService->findById($id);
        $quotation->load('items.product');

        return view('client.quotations.show', compact('quotation'));
    }

    public function download(int $id): StreamedResponse|RedirectResponse
    {
        $quotation = $this->quotationService->findById($id);

        if ($quotation->user_id !== auth()->id()) {
            abort(403);
        }

        $quotation = $this->quotationService->generatePdf($quotation);

        if (!$quotation->pdf_file || !Storage::disk('public')->exists($quotation->pdf_file)) {
            return back()->with('error', 'PDF file not found.');
        }

        return Storage::disk('public')->download($quotation->pdf_file);
    }
}
