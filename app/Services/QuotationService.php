<?php
declare(strict_types=1);

namespace App\Services;

use App\Events\QuotationCreated;
use App\Models\Quotation;
use App\Repositories\QuotationRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

    public function findById(int $id): Model
    {
        return $this->quotationRepository->findById($id);
    }

    public function create(array $data): Model
    {
        $data['quotation_number'] = $this->generateQuotationNumber();
        $data['status'] = $data['status'] ?? 'draft';

        return $this->quotationRepository->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->quotationRepository->update($id, $data);
    }

    public function delete(int $id): bool
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
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }

    public function addItem(int $quotationId, int $productId, float $price, int $quantity): QuotationItem
    {
        return $this->quotationRepository->createItem(
            $quotationId, $productId, $price, $quantity
        );
    }

    public function removeItem(int $quotationId, int $itemId): bool
    {
        return $this->quotationRepository->deleteItem($quotationId, $itemId);
    }

    public function generatePdf(Quotation $quotation): Quotation
    {
        $quotation->load('items.product');

        $pdf = Pdf::loadView('pdfs.quotation', compact('quotation'));
        $filename = "quotations/{$quotation->quotation_number}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        $quotation->pdf_file = $filename;
        $quotation->save();

        QuotationCreated::dispatch($quotation);

        return $quotation;
    }
}
