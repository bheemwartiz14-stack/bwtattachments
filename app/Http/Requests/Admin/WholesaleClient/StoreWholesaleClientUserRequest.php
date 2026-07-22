<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\WholesaleClient;

use Illuminate\Foundation\Http\FormRequest;

class StoreWholesaleClientUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'roles' => $this->input('role', $this->input('roles', 'Wholesale')),
        ]);
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            // Account Details
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],

            'roles' => [
                'required',
                'string',
            ],

            // Company Details
            'wholesale_company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'regex:/^[0-9+\-\s()]{10,20}$/',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],

            'postal_code' => [
                'required',
                'string',
                'max:20',
            ],

            'city' => [
                'required',
                'string',
                'max:255',
            ],

            'country' => [
                'required',
                'string',
                'max:255',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            'vat_number' => [
                'required',
                'string',
                'max:50',
            ],

            'commission_percentage' => [
                'numeric',
                'between:0,100',
                'decimal:0,2',
            ],

            'wholesale_client_logo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],
            'wholesale_client_logo_temp' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The full name is required.',

            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',

            'username.required' => 'The username is required.',
            'username.unique' => 'This username has already been taken.',

            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',

            'roles.required' => 'A role is required.',

            'wholesale_company_name.required' => 'The company name is required.',

            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',

            'address.required' => 'The company address is required.',

            'postal_code.required' => 'The postal code is required.',

            'city.required' => 'The city is required.',

            'country.required' => 'The country is required.',

            'website.url' => 'Please enter a valid website URL.',

            'vat_number.required' => 'The VAT number is required.',

            'commission_percentage.numeric' => 'The commission percentage must be a number.',
            'commission_percentage.between' => 'The commission percentage must be between 0 and 100.',
            'commission_percentage.decimal' => 'The commission percentage may have up to 2 decimal places.',

            'wholesale_client_logo.image' => 'The logo must be an image.',
            'wholesale_client_logo.mimes' => 'The logo must be a JPEG, JPG, PNG, or WebP image.',
            'wholesale_client_logo.max' => 'The logo must not be larger than 2 MB.',
        ];
    }
}
