<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', \App\Models\User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ];
    }

    /**
     * Clean input before validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->status ? 1 : 0,
        ]);
        if (!$this->password) {
            $this->request->remove('password'); // optional but OK in Laravel
        }
    }
}