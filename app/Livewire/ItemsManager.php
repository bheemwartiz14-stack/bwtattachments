<?php
declare(strict_types=1);

namespace App\Livewire;

use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ItemsManager extends Component
{
    public array $items = [];

    public string $search = '';

    public string $deliveryCountry = 'NL';

    public ?string $customerId = null;

    public ?string $productId = null;

    public bool $showModal = false;

    protected ProductService $productService;

    public function boot(ProductService $productService): void
    {
        $this->productService = $productService;
    }

    public function mount(): void
    {
        $items = old('items');
        if ($items) {
            $decoded = is_string($items) ? json_decode($items, true) : $items;
            if (is_array($decoded)) {
                $this->items = $decoded;
            }
        }
        if ($this->productId) {
            $this->addItem($this->productId);
            $this->productId = null;
        }
        if (count($this->items) > 0) {
            $this->recalculatePrices();
            $this->dispatchItemsUpdated();
        }
    }

    public function openModal(): void
    {
        $this->search = '';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->search = '';
    }

    public function addItem(string $productId): void
    {
        if (collect($this->items)->contains('product_id', $productId)) return;

        $userId = $this->customerId ?? Auth::id();

        $product = $this->productService->getActiveProductsWithUserPrices($userId)
            ->firstWhere('id', $productId);

        if (!$product) return;

        $price = $product->productPrices->first()?->final_price ?? $product->ddp_price ?? 0;

        $this->items[] = [
            'product_id' => $product->id,
            'product_title' => $product->product_title,
            'product_code' => $product->product_code,
            'price' => (float) $price,
            'quantity' => 1,
        ];

        $this->dispatchItemsUpdated();
        $this->showModal = false;
        $this->search = '';
    }

    public function removeItem(int $index): void
    {
        if (isset($this->items[$index])) {
            array_splice($this->items, $index, 1);
            $this->dispatchItemsUpdated();
        }
    }

    public function updateQty(int $index, int $value): void
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['quantity'] = max(1, $value);
            $this->dispatchItemsUpdated();
        }
    }

    public function updatePrice(int $index, float $value): void
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['price'] = max(0, $value);
            $this->dispatchItemsUpdated();
        }
    }

    #[On('countryChanged')]
    public function updateCountry($country): void
    {
        $this->deliveryCountry = is_array($country) ? ($country['country'] ?? 'NL') : $country;
        $this->dispatchItemsUpdated();
    }

    #[On('customerIdChanged')]
    public function updateCustomerId($id): void
    {
        $this->customerId = is_array($id) ? ($id['id'] ?? null) : $id;
        $this->recalculatePrices();
    }

    #[On('customerCleared')]
    public function onCustomerCleared(): void
    {
        $this->customerId = null;
        $this->recalculatePrices();
    }

    protected function recalculatePrices(): void
    {
        $userId = $this->customerId ?? Auth::id();

        foreach ($this->items as $i => $item) {
            $product = $this->productService->getActiveProductsWithUserPrices($userId)
                ->firstWhere('id', $item['product_id']);

            if ($product) {
                $price = $product->productPrices->first()?->final_price ?? $product->ddp_price ?? 0;
                $this->items[$i]['price'] = (float) $price;
            }
        }

        if (count($this->items) > 0) {
            $this->dispatchItemsUpdated();
        }
    }

    protected function dispatchItemsUpdated(): void
    {
        $this->dispatch('itemsUpdated', items: $this->items);
    }

    public function getSubtotalProperty(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        return $total;
    }

    public function getTaxRateProperty(): int
    {
        return $this->deliveryCountry === 'NL' ? 21 : 0;
    }

    public function getTaxAmountProperty(): float
    {
        return $this->subtotal * ($this->taxRate / 100);
    }

    public function getGrandTotalProperty(): float
    {
        return $this->subtotal + $this->taxAmount;
    }

    public function render(): View
    {
        $userId = $this->customerId ?? Auth::id();
        // dd($userId);

        $products = $this->productService->getActiveProductsWithUserPrices($userId);

        if ($this->search) {
            $s = strtolower($this->search);
            $products = $products->filter(function ($p) use ($s) {
                return stripos($p->product_title, $s) !== false
                    || stripos($p->product_code ?? '', $s) !== false
                    || stripos($p->product_description ?? '', $s) !== false;
            })->values();
        }

        return view('livewire.items-manager', [
            'products' => $products,
        ]);
    }
}
