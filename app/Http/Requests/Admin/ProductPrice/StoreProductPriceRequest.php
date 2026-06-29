<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\ProductPrice;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'string', 'exists:products,id'],
            'user_id' => ['required', 'string', 'exists:users,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'margin' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'The selected product does not exist.',
            'user_id.required' => 'Please select a wholesale client.',
            'user_id.exists' => 'The selected user does not exist.',
            'price.required' => 'Please enter the wholesale price.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'margin.numeric' => 'Margin must be a valid number.',
            'margin.min' => 'Margin cannot be negative.',
            'margin.max' => 'Margin cannot exceed 999.99%.',
        ];
    }
}
