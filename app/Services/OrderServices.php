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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderServices
{
    public function __construct(protected OrderRepository $orderRepository) {}

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
        $items = $data['items'] ?? [];
        unset($data['items']);
        // Ensure order_reference never null (DB default '')
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

      public function sendEmail(Order $order): void
    {
        $to = $order->toUser?->email ?? \App\Models\User::role('Admin')->first()?->email;
        $logFile = storage_path('logs/emaillogs.txt');
        $altLogFile = base_path('emaillogs.txt');
        $timestamp = now()->format('Y-m-d H:i:s');
        $orderNumber = $order->order_number ?? $order->id;

        if (! $to) {
            $msg = "[$timestamp] To: (none) | Order: $orderNumber | Status: failed | Error: No recipient email found\n";
            @file_put_contents($logFile, $msg, FILE_APPEND);
            @file_put_contents($altLogFile, $msg, FILE_APPEND);
            return;
        }

        try {
            // Ensure PDF exists
            if (! $order->pdf_file || ! Storage::disk('public')->exists($order->pdf_file)) {
                $this->generateOrderPdf($order);
            }
            Mail::to($to)->send(new OrderMail($order));
            $msg = "[$timestamp] To: $to | Order: $orderNumber | Status: sent successfully\n";
            @file_put_contents($logFile, $msg, FILE_APPEND);
            @file_put_contents($altLogFile, $msg, FILE_APPEND);
        } catch (\Throwable $e) {
            $error = str_replace(["\r", "\n"], ' ', $e->getMessage());
            $msg = "[$timestamp] To: $to | Order: $orderNumber | Status: failed | Error: $error\n";
            @file_put_contents($logFile, $msg, FILE_APPEND);
            @file_put_contents($altLogFile, $msg, FILE_APPEND);
            throw $e;
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
        $filename = "orders/{$order->order_number}.pdf";
        $content = Pdf::view('pdf.generate-order-pdf', compact('order'))
            ->format('a4')
            ->orientation('portrait')
            ->driver('dompdf')
            ->generatePdfContent();
        Storage::disk('public')->put($filename, $content);
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
