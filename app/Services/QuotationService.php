<?php
declare(strict_types=1);

namespace App\Services;

use App\Events\QuotationCreated;
use App\Mail\QuotationMail;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Repositories\QuotationRepository;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class QuotationService
{
    public function __construct(protected QuotationRepository $quotationRepository)
    {
    }

    public function getAll(): Collection
    {
        return $this->quotationRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->quotationRepository->paginate($perPage);
    }

    public function findById(string $id): Model
    {
        return $this->quotationRepository->findById($id);
    }

    public function create(array $data): Model
    {
        $data['quotation_number'] = $this->generateQuotationNumber();
        $data['status'] = $data['status'] ?? 'draft';

        return $this->quotationRepository->create($data);
    }

    public function update(string $id, array $data): Model
    {
        return $this->quotationRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->quotationRepository->delete($id);
    }

    public function findByUser(string $userId): Collection
    {
        return $this->quotationRepository->findByUser($userId);
    }

    public function findByStatus(string $status): Collection
    {
        return $this->quotationRepository->findByStatus($status);
    }

    public function generateQuotationNumber(): string
    {
        $prefix = 'QTN';
        $date = now()->format('Ymd');
        $lastQuotation = Quotation::whereDate('created_at', today())
            ->where('quotation_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastQuotation) {
            $lastNumber = (int) substr($lastQuotation->quotation_number, -4);
            $newNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }

    public function addItem(string $quotationId, string $productId, float $price, int $quantity): QuotationItem
    {
        return $this->quotationRepository->createItem(
            $quotationId, $productId, $price, $quantity
        );
    }

    public function removeItem(string $quotationId, int $itemId): bool
    {
        return $this->quotationRepository->deleteItem($quotationId, $itemId);
    }

    public function generatePdf(Quotation $quotation): Quotation
    {
        $quotation->load(['items.product', 'user.userMeta']);
        $filename = "quotations/{$quotation->quotation_number}.pdf";
        $content = Pdf::view('pdf.quotations', compact('quotation')) ->format('A4')->driver('dompdf')->generatePdfContent();
        Storage::disk('public')->put($filename, $content);
        $quotation->pdf_file = $filename;
        $quotation->save();
        QuotationCreated::dispatch($quotation);
        return $quotation;
    }

    public function sendEmail(Quotation $quotation): void
    {
        $to = $quotation->contact_email ?: $quotation->reseller?->email;

        if ($to) {
            Mail::to($to)->send(new QuotationMail($quotation));
        }
    }
}
