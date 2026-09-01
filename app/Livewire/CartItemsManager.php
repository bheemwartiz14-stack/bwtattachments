<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\UserProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartItemsManager extends Component
{
    public array $items = [];
    public string $search = '';
    public string $deliveryCountry = '';
    public ?string $customerId = null;
    public ?string $productId = null;
    public bool $showModal = false;
    protected ProductService $productService;
    protected UserProductService $userProductService;
    public function boot(ProductService $productService, UserProductService $userProductService): void
    {
        $this->productService = $productService;
        $this->userProductService = $userProductService;
    }
    public function mount($productIds = []): void
    {
        $items = old('items');
        if ($items) {
            $decoded = is_string($items) ? json_decode($items, true) : $items;
            if (is_array($decoded) && ! empty($decoded)) {
                // Old input may be array of strings (productIds) or array of item arrays.
                $first = reset($decoded);
                if (is_array($first) && array_key_exists('product_id', $first)) {
                    $this->items = $decoded;
                } elseif (is_string($first)) {
                    // Treat as productIds list – merge into productIds instead of items
                    $productIds = array_merge((array) $productIds, $decoded);
                }
            } elseif (is_array($decoded)) {
                $this->items = $decoded;
            }
        }
        if ($this->productId) {
            $productIds[] = $this->productId;
            $this->productId = null;
        }
        // Sanitize: remove any stray string entries (e.g. from old('items') as productIds)
        if (! empty($this->items)) {
            $this->items = array_values(array_filter($this->items, fn ($v) => is_array($v) && isset($v['product_id'])));
        }
        if (Auth::check() && empty($this->items)) {
            $this->loadCartFromSession();
        }
        foreach (array_unique($productIds) as $id) {
            if (! is_string($id) || $id === '') {
                continue;
            }

            if (! collect($this->items)->contains('product_id', $id)) {
                $this->addItem($id);
            }
        }
        if (count($this->items) > 0) {
            $this->recalculatePrices();
        }
    }
    public function openModal(): void { $this->search = ''; $this->showModal = true; }
    public function closeModal(): void { $this->showModal = false; $this->search = ''; }
    public function addItem(string $productId): void
    {
        if (collect($this->items)->contains('product_id', $productId)) return;
        $userId = $this->customerId ?? Auth::id();
        $product = $this->productService->getActiveProductsWithUserPrices($userId)->firstWhere('id', $productId);
        if (! $product) return;
        $price = $product->productPrices->first()?->final_price ?? $product->ddp_price ?? 0;
        $quantity = 1;
        if (Auth::check()) {
            $this->userProductService->addToCart(Auth::user(), $product, $quantity);
        }
        $this->items[] = ['product_id' => $product->id, 'product_title' => $product->product_title, 'product_code' => $product->product_code, 'price' => (float) $price, 'quantity' => $quantity];
        $this->dispatchItemsUpdated(); $this->dispatchCartUpdated(); $this->showModal = false; $this->search = '';
    }

    public function removeItem(int $index): void
    {
        if (! isset($this->items[$index])) return;
        $item = $this->items[$index];
        if (Auth::check() && ! empty($item['product_id'])) {
            $this->userProductService->removeFromCart(Auth::user(), (string) $item['product_id']);
        }
        array_splice($this->items, $index, 1);
        $this->dispatchItemsUpdated(); $this->dispatchCartUpdated();
    }

    public function updateQty(int $index, int $value): void
    {
        if (! isset($this->items[$index])) return;
        $quantity = min(50, max(1, $value));
        $this->items[$index]['quantity'] = $quantity;
        if (Auth::check()) $this->persistQuantityForIndex($index, $quantity);
        $this->dispatchItemsUpdated(); $this->dispatchCartUpdated();
    }

    public function toggleCart(string $productId): void
    {
        if (! Auth::check()) return;
        $product = Product::find($productId);
        if (! $product) return;
        try {
            $result = $this->userProductService->toggleCart(Auth::user(), $product);
            $added = (bool) ($result['added'] ?? false);
            if ($added) {
                if (! collect($this->items)->contains('product_id', $product->id)) {
                    $price = $product->productPrices->first()?->final_price ?? $product->ddp_price ?? 0;
                    $this->items[] = ['product_id' => $product->id, 'product_title' => $product->product_title, 'product_code' => $product->product_code, 'price' => (float) $price, 'quantity' => $this->userProductService->getCartQuantity(Auth::user(), (string) $product->id)];
                }
            } else {
                $this->items = collect($this->items)->reject(fn ($i) => (string) ($i['product_id'] ?? '') === (string) $product->id)->values()->toArray();
            }
            $this->dispatchItemsUpdated(); $this->dispatchCartUpdated();
        } catch (\Throwable $e) { report($e); }
    }

    #[On('cartUpdated')] public function onCartUpdated(): void { $this->loadCartFromSessionRefresh(); }
    private function loadCartFromSessionRefresh(): void { $this->items = []; $this->loadCartFromSession(); $this->recalculatePrices(); }
    public function increment(string $cartItemId): void { $this->updateQuantity($cartItemId, 1); $this->dispatchCartUpdated(); }
    public function decrement(string $cartItemId): void { $this->updateQuantity($cartItemId, -1); $this->dispatchCartUpdated(); }

    private function dispatchCartUpdated(): void
    {
        if (! Auth::check()) {
            return;
        }
        $count = $this->userProductService->getCartCount(Auth::user());
        $this->dispatch('cartUpdated', count: $count);
    }
    private function updateQuantity(string $cartItemId, int $delta): void
    {
        if (! Auth::check()) return;
        try {
            $item = collect($this->items)->first(fn ($item) => (string) ($item['product_id'] ?? '') === $cartItemId);
            if (! $item) return;
            $currentQty = (int) ($item['quantity'] ?? 1);
            $newQty = $currentQty + $delta;
            if ($newQty < 1) return;
            $newQty = min(50, max(1, $newQty));
            $this->userProductService->updateCartQuantity(Auth::user(), $cartItemId, $newQty);
            foreach ($this->items as $i => $item) {
                if ((string) ($item['product_id'] ?? '') === $cartItemId) { $this->items[$i]['quantity'] = $newQty; break; }
            }
            $this->dispatchItemsUpdated();
        } catch (\Throwable $e) { report($e); }
    }
    private function persistQuantityForIndex(int $index, int $quantity): void
    {
        $item = $this->items[$index] ?? null;
        if (! $item) return;
        $this->userProductService->updateCartQuantity(Auth::user(), (string) $item['product_id'], $quantity);
    }
    private function loadCartFromSession(): void
    {
        if (! Auth::check()) return;
        try {
            $userId = $this->customerId ?? Auth::id();
            $productsWithPrices = $this->productService->getActiveProductsWithUserPrices($userId)->keyBy('id');
            foreach ($this->userProductService->getQuotationItems(Auth::user()) as $product) {
                $pricedProduct = $productsWithPrices->get($product->id);
                $price = $pricedProduct?->productPrices->first()?->final_price ?? $product->ddp_price ?? 0;
                $this->items[] = ['product_id' => $product->id, 'product_title' => $product->product_title, 'product_code' => $product->product_code, 'price' => (float) $price, 'quantity' => $this->userProductService->getCartQuantity(Auth::user(), (string) $product->id)];
            }
        } catch (\Throwable $e) { report($e); }
    }
    public function updatePrice(int $index, float $value): void { if (isset($this->items[$index])) { $this->items[$index]['price'] = max(0, $value); $this->dispatchItemsUpdated(); } }
    #[On('countryChanged')] public function updateCountry($country): void { $this->deliveryCountry = is_array($country) ? ($country['country'] ?? 'NL') : $country; $this->dispatchItemsUpdated(); }
    #[On('customerIdChanged')] public function updateCustomerId($id): void { $this->customerId = is_array($id) ? ($id['id'] ?? null) : $id; $this->recalculatePrices(); }
    #[On('customerCleared')] public function onCustomerCleared(): void { $this->customerId = null; $this->recalculatePrices(); }
    protected function recalculatePrices(): void
    {
        $userId = $this->customerId ?? Auth::id();
        foreach ($this->items as $i => $item) {
            if (! is_array($item) || ! isset($item['product_id'])) {
                continue;
            }
            $product = $this->productService->getActiveProductsWithUserPrices($userId)->firstWhere('id', $item['product_id']);
            if ($product) { $price = $product->productPrices->first()?->final_price ?? $product->ddp_price ?? 0; $this->items[$i]['price'] = (float) $price; }
        }
        if (count($this->items) > 0) $this->dispatchItemsUpdated();
    }
    protected function dispatchItemsUpdated(): void { $this->dispatch('itemsUpdated', items: $this->items); }
    public function getSubtotalProperty(): float { $total = 0; foreach ($this->items as $item) { if (! is_array($item)) continue; $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1); } return $total; }
    public function getTaxRateProperty(): int { return $this->deliveryCountry === 'NL' ? 21 : 0; }
    public function getTaxAmountProperty(): float { return $this->subtotal * ($this->taxRate / 100); }
    public function getGrandTotalProperty(): float { return $this->subtotal + $this->taxAmount; }
    public function render(): \Illuminate\Contracts\View\View
    {
        $userId = $this->customerId ?? Auth::id();
        $products = $this->productService->getActiveProductsWithUserPrices($userId);
        if ($this->search) { $s = strtolower($this->search); $products = $products->filter(fn ($p) => stripos($p->product_title, $s) !== false || stripos($p->product_code ?? '', $s) !== false || stripos($p->product_description ?? '', $s) !== false)->values(); }
        return view('livewire.cart-items-manager', ['products' => $products]);
    }
}
