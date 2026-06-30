<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('quotation.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $items = json_decode($this->input('items', '[]'), true);

        $this->merge([
            'items' => is_array($items) ? $items : [],
        ]);
    }

    public function rules(): array
    {
        return [
            'margin_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
