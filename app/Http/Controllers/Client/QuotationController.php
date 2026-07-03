<?php
declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotations\StoreQuotationRequest;
use App\Models\Quotation;
use App\Models\User;
use App\Services\QuotationService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $resellers = User::where('parent_id', auth()->id())
            ->role('Retailer')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);

        $quotationNumber = $this->quotationService->generateQuotationNumber();

        return view('client.quotations.create', compact('resellers', 'quotationNumber'));
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
            return redirect()->route('client.quotations.preview', $quotation->id) ->with('success', 'Quotation created and PDF generated successfully.');
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

        return redirect()->route('client.quotations.show', $quotation->id)
            ->with('success', $message);
    }

  public function preview(string $id): BinaryFileResponse
{
    return $this->quotationService->previewPdf($id);
}

    public function show(string $id): View
    {
        $quotation = $this->quotationService->findById($id);
        $quotation->load('items.product');

        return view('client.quotations.show', compact('quotation'));
    }

    public function download(string $id): StreamedResponse|RedirectResponse
    {
        $quotation = $this->quotationService->findById($id);
        $quotation = $this->quotationService->generatePdf($quotation);

        if (!$quotation->pdf_file || !Storage::disk('public')->exists($quotation->pdf_file)) {
            return back()->with('error', 'PDF file not found.');
        }

        return Storage::disk('public')->download($quotation->pdf_file);
    }

    public function generatePdf(string $id): RedirectResponse
    {
        $quotation = $this->quotationService->findById($id);

        if ($quotation->user_id !== auth()->id()) {
            abort(403);
        }

        $this->quotationService->generatePdf($quotation);

        return back()->with('success', 'PDF generated successfully.');
    }

    public function sendEmail(string $id): RedirectResponse
    {
        $quotation = $this->quotationService->findById($id);

        if ($quotation->user_id !== auth()->id()) {
            abort(403);
        }

        $quotation->load('reseller');

        if (!$quotation->pdf_file) {
            $this->quotationService->generatePdf($quotation);
        }

        $this->quotationService->sendEmail($quotation);

        return back()->with('success', 'Quotation PDF sent to reseller successfully.');
    }
}
