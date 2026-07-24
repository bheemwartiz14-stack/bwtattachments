<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\UserProductService;
use Illuminate\Http\JsonResponse;

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
        return response()->json(
            $this->userProductService->toggleCart(auth()->user(), $product)
        );
    }
}