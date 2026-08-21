<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceHierarchyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->resource['user_id'] ?? null,

            'role_name' => $this->resource['role_name'] ?? null,

            'product_id' => $this->resource['product_id'] ?? null,

            'parent_price' => isset($this->resource['parent_price'])
                ? (float) $this->resource['parent_price']
                : null,

            'margin_type' => $this->resource['margin_type'] ?? null,

            'margin_price' => isset($this->resource['user_margin_price'])
                ? (float) $this->resource['user_margin_price']
                : null,

            'product_price' => isset($this->resource['product_price'])
                ? (float) $this->resource['product_price']
                : null,

            'children' => self::collection(
                $this->resource['children'] ?? []
            ),
        ];
    }
}