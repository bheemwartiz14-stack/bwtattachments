<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Profile;

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

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar_temp' => ['nullable', 'string'],
            'country_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country_code' => ['sometimes', 'nullable', 'string', 'max:10'],
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'vat_number' => ['sometimes', 'required', 'string', 'max:50'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'required', 'string', 'max:20'],
            'city' => ['sometimes', 'required', 'string', 'max:100'],
            'wholesale_client_logo' => ['nullable', 'file', 'extensions:jpeg,jpg,png,webp', 'max:2048'],
            'wholesale_client_logo_temp' => ['nullable', 'string'],
        ];
    }
}
