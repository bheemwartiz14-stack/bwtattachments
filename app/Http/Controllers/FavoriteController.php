<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    public function toggle(Product $product): JsonResponse
    {
        $user = auth()->user();

        if ($user->favoriteProducts()->where('product_id', $product->id)->exists()) {
            $user->favoriteProducts()->detach($product);
            $favorited = false;
            $message = 'Removed from favorites';
        } else {
            $user->favoriteProducts()->attach($product);
            $favorited = true;
            $message = 'Added to favorites';
        }

        return response()->json([
            'favorited' => $favorited,
            'message' => $message,
        ]);
    }
}
