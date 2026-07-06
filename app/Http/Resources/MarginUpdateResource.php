<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarginUpdateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->resource['product'];
        $userData = $this->resource['userData'];
        $userPrice = $product->productPrices->first();
        $basePrice = (float) ($userPrice?->base_price ?? $product->ddp_price ?? 0);
        $marginType = $userData->margin_type ?? '';
        $marginValue = (float) ($userData->margin_value ?? 0);
        $finalPrice = $marginType === 'value' ? $basePrice + $marginValue : $basePrice + ($basePrice * $marginValue / 100);
        return [
            'product_id' => $product->id,
            'user_id' => $userData->user_id,
            'type' => $userData->type ?? '',
            'role_name' => $userData->role_name ?? '',
            'base_price' => round($basePrice, 2),
            'margin' => $marginValue,
            'margin_type' => $marginType,
            'margin_value' => $marginValue,
            'final_price' => round($finalPrice, 2),
        ];
    }
}
