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
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email:rfc,dns',
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

            'retailer_client_name' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_id' => [
                'required',
                'string',
                'exists:users,id',
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s()]{10,20}$/',
            ],

            'retailer_client_logo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],

            'commission_percentage' => [
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
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
            'password.confirmed' => 'The password confirmation does not match.',
            'roles.required' => 'Please select a role.',
            'retailer_client_name.required' => 'The retailer name is required.',
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'retailer_client_logo.image' => 'The uploaded file must be an image.',
            'retailer_client_logo.mimes' => 'The logo must be a jpeg, jpg, png, or webp file.',
            'retailer_client_logo.max' => 'The logo may not be greater than 2MB.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'retailer_client_name' => 'retailer name',
            'retailer_client_logo' => 'retailer logo',
        ];
    }
}
