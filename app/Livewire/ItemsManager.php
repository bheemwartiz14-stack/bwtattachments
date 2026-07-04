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

    public float $marginPercentage = 0;

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
        $margin = old('margin_percentage');
        if ($margin !== null) {
            $this->marginPercentage = (float) $margin;
        }
        if (count($this->items) > 0) {
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

        $product = $this->productService->getActiveProductsWithUserPrices(Auth::id())
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

    #[On('marginChanged')]
    public function updateMargin($margin): void
    {
        $this->marginPercentage = (float) (is_array($margin) ? ($margin['margin'] ?? 0) : $margin);
        $this->dispatchItemsUpdated();
    }

    protected function dispatchItemsUpdated(): void
    {
        $this->dispatch('itemsUpdated', items: $this->items, margin: $this->marginPercentage);
    }

    public function getSubtotalProperty(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        return $total;
    }

    public function getMarginAmountProperty(): float
    {
        return $this->subtotal * ($this->marginPercentage / 100);
    }

    public function getBeforeTaxProperty(): float
    {
        return $this->subtotal + $this->marginAmount;
    }

    public function getTaxRateProperty(): int
    {
        return $this->deliveryCountry === 'NL' ? 21 : 0;
    }

    public function getTaxAmountProperty(): float
    {
        return $this->beforeTax * ($this->taxRate / 100);
    }

    public function getGrandTotalProperty(): float
    {
        return $this->beforeTax + $this->taxAmount;
    }

    public function render(): View
    {
        $products = $this->productService->getActiveProductsWithUserPrices(Auth::id());

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
