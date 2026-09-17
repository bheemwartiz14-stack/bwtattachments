<?php

declare(strict_types=1);

namespace App\Http\Requests\Reseller\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar_temp' => ['nullable', 'string'],
            'country_name' => ['sometimes', 'string', 'max:255'],
            'country_code' => ['nullable',  'string', 'max:10'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'vat_number' => ['sometimes', 'required', 'string', 'max:50'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'required', 'string', 'max:20'],
            'city' => ['nullable','string', 'max:100'],
        ];
    }
}
