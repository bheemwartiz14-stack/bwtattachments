<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\WholesaleClient;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWholesaleClientUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->password)) {
            $this->request->remove('password');
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'wholesale_client_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{10,15}$/'],
            'wholesale_client_logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'wholesale_client_name.required' => 'Wholesale client name is required.',

            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',

            'wholesale_client_logo.image' => 'The logo must be an image.',
            'wholesale_client_logo.max' => 'Logo must not exceed 2MB.',
        ];
    }
}