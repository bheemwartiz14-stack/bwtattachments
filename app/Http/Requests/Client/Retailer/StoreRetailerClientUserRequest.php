<?php
declare(strict_types=1);

namespace App\Http\Requests\Client\Retailer;

use Illuminate\Foundation\Http\FormRequest;

class StoreRetailerClientUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{10,20}$/'],
            'parent_id' => ['required', 'string', 'exists:users,id'],
            'roles' => ['required', 'string'],
            'retailer_client_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'commission_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'company_name' => ['required', 'string', 'max:255'],
            'vat_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
             'retailer_client_logo_temp' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'username.required' => 'The username is required.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'retailer_client_logo.image' => 'The uploaded file must be an image.',
            'retailer_client_logo.mimes' => 'The logo must be a jpeg, jpg, png, or webp file.',
            'retailer_client_logo.max' => 'The logo may not be greater than 2MB.',
            'company_name.required' => 'The company name field is required.',
            'vat_number.required' => 'The VAT number field is required.',
            'address.required' => 'The address field is required.',
            'postal_code.required' => 'The postal code field is required.',
            'city.required' => 'The city field is required.',
            'country.required' => 'The country field is required.',
            'website.url' => 'Please enter a valid URL.',
        ];
    }

    public function attributes(): array
    {
        return [
            'retailer_client_logo' => 'retailer logo',
            'company_name' => 'company name',
            'vat_number' => 'VAT number',
            'postal_code' => 'postal code',
        ];
    }
}
