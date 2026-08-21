<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WholesalerHierarchyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'role_name' => $this->getRoleNames()->first(),
            'username' => $this->username,
            "marginType"=>  $this->userMargin?->margin_type,
            'user_margin_price' => $this->userMargin?->margin_value,
            'children' => $this->children->map(function ($child) use ($request) {
                return (new self($child))->toArray($request);
            })->values()->toArray(),
        ];
    }
}