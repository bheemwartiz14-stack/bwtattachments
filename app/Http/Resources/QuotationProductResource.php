<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->resource?->product;
        if (!$product) {
            return [];
        }
        return [
            'product_id' => $product->id,
            'product_title' => $product->product_title,
            'product_code' => $product->product_code,
            'price' => $product->price,
            'quantity' => 1,
        ];
    }
}
