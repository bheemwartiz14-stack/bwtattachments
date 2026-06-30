<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPricingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->resource['product'];
        $user = $this->resource['user'];
        $basePrice = (float) ($product->ddp_price ?? 0);
        $marginType = $user->userMargin?->margin_type ?? 'percentage';
        $marginValue = (float) ($user->userMargin?->margin_value ?? 0);
        $roleName = $user->roles->pluck('name')->first();
        $finalPrice = $marginType === 'value' ? $basePrice + $marginValue   : $basePrice + ($basePrice * $marginValue / 100);
        return [
            'product_id'   => $product->id,
            'user_id'      => $user->id,
            'type'         => $roleName === 'Wholesale Client' ? 'wholesale' : 'retailer',
            'role_name'    => $roleName,
            'base_price'   => round($basePrice, 2),
            'final_price'  => round($finalPrice, 2),
            'margin'       => $marginValue,
            'margin_type'  => $marginType,
            'margin_value' => $marginValue,
        ];
    }
}
