<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('product.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'product_code' => ['sometimes', 'string', 'max:255', Rule::unique('products')->ignore($this->route('product'))],
            'product_description' => ['sometimes', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'connection_id' => ['nullable', 'exists:connections,id'],
            'weight' => ['nullable', 'numeric'],
            'machine_weight' => ['nullable', 'numeric'],
            'hinges' => ['nullable', 'string', 'max:255'],
            'width' => ['nullable', 'numeric'],
            'volume' => ['nullable', 'numeric'],
            'cutting_edge_thickness' => ['nullable', 'numeric'],
            'teeth' => ['nullable', 'string', 'max:255'],
            'stick_width' => ['nullable', 'numeric'],
            'pin_center' => ['nullable', 'numeric'],
            'pin_hole' => ['nullable', 'numeric'],
            'ddp_price' => ['nullable', 'numeric'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'internal_notes' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }
}
