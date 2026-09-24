<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\OrderCreated;
use App\Mail\OrderMail;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Services\FileService;
use Spatie\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderServices
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected FileService $fileService,
        ) {}

    // generate a unique order number based on the current timestamp
    public function generateOrderNumber(): string
    {
        $year = now()->format('Y');
        $lastOrder = Order::where('order_number', 'like', "BWT-{$year}-%") ->orderByDesc('id')->first();
        $nextNumber = $lastOrder ? ((int) substr($lastOrder->order_number, -4)) + 1 : 244;
        return 'BWT-' . $year . '-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data): Model
    {
        $orderlogotype = $data['orderlogotype'] ?? null;
        // The form submits a full URL; normalize to a public-disk-relative
        // path (what copyFile() and OrderMail expect).
        $orderfilepath = $this->toPublicDiskPath($data['orderfilepath'] ?? null);
        // Only a custom logo is ever stored/sent. The standard B-logo lives
        // with BWT already, so "big" (and "none") never produce a file.
        if ($orderlogotype !== 'custom' || !$orderfilepath) {
            $data['orderlogotype'] = $orderlogotype ?: 'none';
            $data['orderfilepath'] = '';
        } else {
            $filename = "orders/{$data['order_number']}/{$data['order_number']}_" . basename($orderfilepath);
            $data['orderfilepath'] = $this->fileService->copyFile(
                $orderfilepath,
                $filename
            );
        }
        $items = $data['items'] ?? [];
        unset($data['items']);
        $data['order_reference'] = $data['order_reference'] ?? '';
        $data['status'] = $data['status'] ?? 'draft';
        $order = $this->orderRepository->create($data);
        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'price' => $item['price'] ?? 0,
                'quantity' => $item['quantity'] ?? 1,
            ]);
        }
        return $order->load('items.product');
    }

    /**
     * Normalize a submitted logo value (full URL or relative path) to a
     * public-disk-relative path, e.g. "temp/{token}/logo.ai".
     */
    private function toPublicDiskPath(mixed $value): string
    {
        if (! is_string($value) || $value === '') {
            return '';
        }

        $path = parse_url($value, PHP_URL_PATH) ?? $value;
        $path = ltrim(urldecode((string) $path), '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return $path;
    }
      /**
       * Resolve a validated recipient for the order email.
       * Sanitizes line breaks and falls back to the admin address.
       * Returns null when no usable address exists.
       */
      public function resolveEmailRecipient(Order $order): ?string
      {
          // Sanitize recipient (avoid line-break injection)
          $rawTo = $order->toUser?->email ?? \App\Models\User::role('Admin')->first()?->email;
          $to = is_string($rawTo) ? trim(str_replace(["\r", "\n"], '', $rawTo)) : $rawTo;
          if (! $to || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
              $fallback = \App\Models\User::role('Admin')->first()?->email;
              $fallback = is_string($fallback) ? trim(str_replace(["\r", "\n"], '', $fallback)) : $fallback;
              $to = filter_var($fallback, FILTER_VALIDATE_EMAIL) ? $fallback : null;
          }

          return $to;
      }

      /**
       * Dispatch the order email (sent synchronously, no queue service
       * in use). Returns false when the address is invalid or sending
       * failed, so controllers can show a friendly error instead of
       * a 500 or a false success message.
       */
      public function sendEmail(Order $order): bool
      {
        $orderNumber = $order->order_number ?? $order->id;

        if ($this->resolveEmailRecipient($order) === null) {
            Log::warning("Order email skipped: no recipient email found for order {$orderNumber}.");

            return false;
        }

        try {
            // Dispatch new listener SendOrderEmail which does Mail::to($to)->send(new OrderMail($order))
            event(new \App\Events\OrderEmailRequested($order));

            return true;
        } catch (\Throwable $e) {
            Log::error('Order email failed', [
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
    public function findById(string $id): Model
    {
        return $this->orderRepository->findById($id);
    }

    public function getAll(): Collection
    {
        return $this->orderRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->paginate($perPage);
    }

    public function findByUser(string $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->findByUser($userId, $perPage);
    }

    public function findByUserCollection(string $userId): Collection
    {
        return $this->orderRepository->findByUserCollection($userId);
    }

    public function update(string $id, array $data): Model
    {
        return $this->orderRepository->update($id, $data);
    }

    public function createItem(string $orderId, string $productId, float $price, int $quantity): \App\Models\OrderItems
    {
        return $this->orderRepository->createItem($orderId, $productId, $price, $quantity);
    }

    public function generateOrderPdf(Order $order): Order
    {

        $order->load([
            'items.product',
            'fromUser.userMeta',
            'toUser.userMeta',
        ]);
       $filename = "orders/{$order->order_number}/{$order->order_number}.pdf";
        $content = Pdf::view('pdf.generate-order-pdf', compact('order'))
            ->format('a4')
            ->orientation('portrait')
            ->driver('dompdf')
            ->generatePdfContent();
        $this->fileService->storeFile($filename, $content);
        $order->pdf_file = $filename;
        $order->save();
        OrderCreated::dispatch($order);
        return $order;
    }

    public function previewPdf(string $orderId): BinaryFileResponse
    {
        /** @var Order $order */
        $order = $this->findById($orderId);
        $order = $this->generateOrderPdf($order);

        return response()->file(
            Storage::disk('public')->path($order->pdf_file),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.basename($order->pdf_file).'"',
            ]
        );
    }

    public function downloadPdf(Order $order): BinaryFileResponse
    {
        $order = $this->generateOrderPdf($order);

        return response()->download(
            Storage::disk('public')->path($order->pdf_file),
            basename($order->pdf_file),
            ['Content-Type' => 'application/pdf']
        );
    }


}
?>
