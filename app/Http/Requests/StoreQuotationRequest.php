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
            'issue_date' => $this->input('issue_date', now()->format('Y-m-d')),
        ]);
    }

    public function rules(): array
    {
        return [
            'reseller_id' => ['required', 'exists:users,id'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'valid_until' => ['required', 'date', 'after:today'],
            'issue_date' => ['required', 'date'],
            'margin_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
