<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\UserProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserProductController extends Controller
{
    public function __construct(
        private readonly UserProductService $userProductService,
    ) {}

    public function toggleFavorite(Product $product): JsonResponse
    {
        return response()->json(
            $this->userProductService->toggleFavorite(auth()->user(), $product)
        );
    }

    public function toggleCart(Product $product): JsonResponse
    {
        $result = $this->userProductService->toggleCart(auth()->user(), $product);

        return response()->json([
            'success' => true,
            'added' => $result['added'],
            'cartCount' => $result['count'],
            'count' => $result['count'],
            'message' => $result['message'],
        ]);
    }

    public function cart(): View
    {
        $user = auth()->user();
        $cartItems = $this->userProductService->getQuotationItems($user);

        $cartIds = $cartItems->pluck('product_id')->toArray();

        return view('pages.public.cart.index', compact('cartItems', 'cartIds'));
    }
}
