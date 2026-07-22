<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class QuoteCartController extends Controller
{
    public function toggle(Product $product): JsonResponse
    {
        $cart = session()->get('quote_cart', []);
        $index = array_search($product->id, array_column($cart, 'product_id'));

        if ($index !== false) {
            array_splice($cart, $index, 1);
            $added = false;
            $message = 'Removed from quotation';
        } else {
            $cart[] = ['product_id' => $product->id, 'quantity' => 1];
            $added = true;
            $message = 'Added to quotation';
        }

        session()->put('quote_cart', $cart);

        return response()->json([
            'added' => $added,
            'count' => count($cart),
            'message' => $message,
        ]);
    }

    public function count(): JsonResponse
    {
        return response()->json([
            'count' => count(session()->get('quote_cart', [])),
        ]);
    }

    public function clear(): JsonResponse
    {
        session()->forget('quote_cart');
        return response()->json(['message' => 'Cart cleared']);
    }
}
