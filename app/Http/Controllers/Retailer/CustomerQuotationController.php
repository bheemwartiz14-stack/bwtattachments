<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Retailer\Quotations\StoreQuotationRequest;
use App\Services\QuotationService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\View\View;

class CustomerQuotationController extends Controller
{
    public function __construct(
        protected QuotationService $quotationService,
        protected UserService $userService,
    ) {}

    public function index(): View
    {
        $quotations = $this->quotationService->findByUser(auth()->id());
        return view('pages.private.retailer.quotations.index', compact('quotations'));
    }

    public function create(): View
    {
        $resellers = $this->userService->getMyCustomers(auth()->id());
        $quotationNumber = $this->quotationService->generateQuotationNumber(auth()->id());

        return view('pages.private.retailer.quotations.create', compact('resellers', 'quotationNumber'));
    }

    public function store(StoreQuotationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = $request->input('action', 'draft') === 'send' ? 'sent' : 'draft';
        $quotation = $this->quotationService->create($data);
        foreach ($data['items'] as $item) {
            $this->quotationService->addItem(
                $quotation->id,
                $item['product_id'],
                (float) ($item['price'] ?? 0),
                (int) $item['quantity']
            );
        }
        if ($request->input('action') === 'pdf' || $request->input('action') === 'send') {
            $this->quotationService->generatePdf($quotation);
        }
        if ($request->input('action') === 'preview') {
            $this->quotationService->generatePdf($quotation);

            return redirect()->route('retailer.quotations.preview', $quotation->id)->with('success', 'Quotation created and PDF generated successfully.');
        }
        if ($request->input('action') === 'send') {
            $quotation->load('reseller');
            $this->quotationService->sendEmail($quotation);
        }

        $message = match ($request->input('action')) {
            'pdf' => 'Quotation created and PDF generated successfully.',
            'send' => 'Quotation created, PDF generated, and sent to reseller successfully.',
            default => 'Quotation saved as draft successfully.',
        };

        return redirect()->route('retailer.quotations.show', $quotation->id)
            ->with('success', $message);
    }

    public function show(string $id): View
    {
        $quotation = $this->quotationService->findById($id);
        $quotation->load('items.product');
        if ($quotation->user_id !== auth()->id()) {
            abort(403);
        }

        return view('retailer.quotations.show', compact('quotation'));
    }

    public function preview(string $id): BinaryFileResponse
    {
        return $this->quotationService->previewPdf($id);
    }

    public function download(string $id): mixed
    {
        $quotation = $this->quotationService->findById($id);

        if ($quotation->user_id !== auth()->id()) {
            abort(403);
        }

        return $this->quotationService->downloadPdf($quotation);
    }
}
