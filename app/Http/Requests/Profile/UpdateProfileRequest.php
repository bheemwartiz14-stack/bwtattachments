<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();
        $userId = $user?->id;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $userId],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if ($user?->hasRole('Wholesale Client')) {
            $rules['wholesale_client_name'] = ['required', 'string', 'max:255'];
            $rules['wholesale_client_logo'] = ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'];
        }

        if ($user?->hasRole('Retailer')) {
            $rules['retailer_client_name'] = ['required', 'string', 'max:255'];
            $rules['retailer_client_logo'] = ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'];
        }

        return $rules;
    }
}
