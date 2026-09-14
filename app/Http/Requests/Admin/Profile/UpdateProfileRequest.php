<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Profile;

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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar_temp' => ['nullable', 'string'],
            'country_name' => [ 'sometimes', ],
            'country_code' => ['sometimes' ],
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'vat_number' => ['sometimes', 'required', 'string', 'max:50'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'required', 'string', 'max:20'],
            'city' => ['sometimes','string', 'max:100'],
            'company_logo' => ['nullable', 'file', 'extensions:jpeg,png,jpg,webp', 'max:10240'],
            'company_logo_temp' => ['nullable', 'string'],
        ];
    }
}
