<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\WholesaleClient;

use Illuminate\Foundation\Http\FormRequest;

class StoreWholesaleClientUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'roles' => $this->input('role', $this->input('roles', 'Wholesale Client')),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', 'max:255', 'unique:users,email'],
            'username'               => ['required', 'string', 'max:255', 'unique:users,username'],
            'password'               => ['required', 'string', 'min:8'],

            'roles'                  => ['required', 'string'],

            'wholesale_company_name'  => ['required', 'string', 'max:255'],
            'phone'                  => ['required', 'regex:/^[0-9+\-\s()]{10,15}$/'],

            'address'                => ['required', 'string', 'max:500'],
            'postal_code'            => ['required', 'string', 'max:20'],
            'city'                   => ['required', 'string', 'max:255'],
            'country'                => ['required', 'string', 'max:255'],
            'website'                => ['nullable', 'url', 'max:255'],
            'vat_number'             => ['required', 'string', 'max:50'],

            'wholesale_client_logo'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'roles.required' => 'A role is required.',

            'wholesale_company_name.required' => 'Company name is required.',

            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',

            'address.required' => 'Company address is required.',
            'postal_code.required' => 'Postal code is required.',
            'city.required' => 'City is required.',
            'country.required' => 'Country is required.',
            'website.url' => 'Please enter a valid URL.',

            'wholesale_client_logo.image' => 'The logo must be an image.',
            'wholesale_client_logo.max' => 'Logo must not exceed 2MB.',
        ];
    }
}