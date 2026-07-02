<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarginUpdateResourceWholesale extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->resource['product'];
        $user = $this->resource['user'];

        $basePrice = (float) ($product->ddp_price ?? 0);
        $marginValue = (float) ($user->userMargin?->margin_value ?? 0);
        $finalPrice = $basePrice + ($basePrice * $marginValue / 100);

        return [
            'product_id'   => $product->id,
            'user_id'      => $user->id,
            'type'         => 'wholesale',
            'base_price'   => round($basePrice, 2),
            'final_price'  => round($finalPrice, 2),
            'margin'       => $marginValue,
        ];
    }
}
