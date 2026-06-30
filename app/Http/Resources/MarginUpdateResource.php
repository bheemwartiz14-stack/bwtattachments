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
        $user = $this->resource['user'];

        $userPrice = $product->productPrices->first();
        $basePrice = (float) ($userPrice?->final_price ?? $product->ddp_price ?? 0);
        $marginType = $user->userMargin?->margin_type ?? 'percentage';
        $marginValue = (float) ($user->userMargin?->margin_value ?? 0);
        $finalPrice = $marginType === 'value'
            ? $basePrice + $marginValue
            : $basePrice + ($basePrice * $marginValue / 100);

        return [
            'product_id'   => $product->id,
            'user_id'      => $user->id,
            'type'         => $user->userMargin?->type ?? 'wholesale',
            'role_name'    => $user->roles->pluck('name')->first(),
            'base_price'   => round($basePrice, 2),
            'final_price'  => round($finalPrice, 2),
            'margin'       => $marginValue,
            'margin_type'  => $marginType,
            'margin_value' => $marginValue,
        ];
    }
}
