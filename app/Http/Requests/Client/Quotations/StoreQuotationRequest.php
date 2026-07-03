<?php

declare(strict_types=1);

namespace App\Http\Requests\Quotations;

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
            'issue_date' => $this->input('issue_date', now()->format('Y-m-d')),
        ]);
    }

    public function rules(): array
    {
        return [
            'reseller_id' => ['required', 'exists:users,id'],
            'quotation_number' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'date', 'after:today'],
            'issue_date' => ['required', 'date'],
            'margin_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'vat_percentage' => ['required', 'string'],
            'sub_total' => ['required', 'string'],
            'tax_amount' => ['required', 'string'],
            'margin_amount' => ['required', 'string'],
            'grand_total' => ['required', 'string'],
            'delivery_country' => ['required', 'string', 'size:2'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            '',
        ];
    }
}
