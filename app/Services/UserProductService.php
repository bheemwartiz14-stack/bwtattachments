<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Repositories\UserProductRepository;

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
        $userProduct = $this->userProductRepository->firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        $userProduct->is_quotation = !$userProduct->is_quotation;
        $this->userProductRepository->save($userProduct);
        $inCart = $userProduct->is_quotation;
        return [
            'added' => $inCart,
            'count' => $this->getCartCount($user),
            'message' => $inCart ? 'Added to cart' : 'Removed from cart',
        ];
    }

    public function getCartCount(User $user): int
    {
        return $this->userProductRepository->countByUser($user->id, 'is_quotation');
    }

    public function getQuotationProductIds(User $user): array
    {
        return $this->userProductRepository->getProductIdsByColumn($user->id, 'is_quotation');
    }
}
