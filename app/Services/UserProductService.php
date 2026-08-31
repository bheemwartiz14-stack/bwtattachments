<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Repositories\UserProductRepository;
use Illuminate\Database\Eloquent\Collection;

class UserProductService
{
    public function __construct(
        protected UserProductRepository $userProductRepository,
    ) {}


    public function toggleFavorite(User $user, Product $product): array
    {
        $userProduct = $this->userProductRepository->firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        $userProduct->is_favorite = !$userProduct->is_favorite;
        $this->userProductRepository->save($userProduct);
        $favorited = $userProduct->is_favorite;
        return [
            'favorited' => $favorited,
            'message' => $favorited ? 'Added to favorites' : 'Removed from favorites',
        ];
    }


    public function toggleCart(User $user, Product $product): array
    {
        $cart = $this->cart($user);
        $productId = (string) $product->id;

        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]);
            $inCart = false;
        } else {
            $cart[$productId] = 1;
            $inCart = true;
        }

        $this->storeCart($user, $cart);
        return [
            'added' => $inCart,
            'count' => $this->getCartCount($user),
             'message' => $inCart ? 'Added to quotation' : 'Removed from quotation',
        ];
    }

    public function getCartCount(User $user): int
    {
        return count($this->cart($user));
    }

    /** @return Collection<int, Product> */
    public function getQuotationItems(User $user): Collection
    {
        $productIds = $this->getQuotationProductIds($user);

        if ($productIds === []) {
            return new Collection();
        }

        return Product::query()
            ->whereIn('id', $productIds)
            ->with(['category', 'media'])
            ->get();
    }

    public function getQuotationProductIds(User $user): array
    {
        return array_keys($this->cart($user));
    }

    public function getCartQuantity(User $user, string $productId): int
    {
        return max(1, (int) ($this->cart($user)[$productId] ?? 1));
    }

    public function addToCart(User $user, Product $product, int $quantity = 1): void
    {
        $cart = $this->cart($user);
        $cart[(string) $product->id] = min(50, max(1, $quantity));
        $this->storeCart($user, $cart);
    }

    public function removeFromCart(User $user, string $productId): void
    {
        $cart = $this->cart($user);
        unset($cart[$productId]);
        $this->storeCart($user, $cart);
    }

    public function updateCartQuantity(User $user, string $productId, int $quantity): void
    {
        $cart = $this->cart($user);
        if (! array_key_exists($productId, $cart)) {
            return;
        }

        $cart[$productId] = min(50, max(1, $quantity));
        $this->storeCart($user, $cart);
    }

    /** @return array<string, int> */
    private function cart(User $user): array
    {
        $cart = session()->get($this->cartSessionKey($user), []);

        return is_array($cart) ? $cart : [];
    }

    /** @param array<string, int> $cart */
    private function storeCart(User $user, array $cart): void
    {
        session()->put($this->cartSessionKey($user), $cart);
    }

    private function cartSessionKey(User $user): string
    {
        return 'quotation_cart_'.(string) $user->id;
    }
}
