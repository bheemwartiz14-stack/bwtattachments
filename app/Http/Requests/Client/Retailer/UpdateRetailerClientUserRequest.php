<?php
declare(strict_types=1);

namespace App\Http\Requests\Client\Retailer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRetailerClientUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'parent_id' => ['nullable', 'string', 'exists:users,id'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{10,15}$/'],
            'retailer_client_name' => ['required', 'string', 'max:255'],
            'retailer_client_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'commission_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
