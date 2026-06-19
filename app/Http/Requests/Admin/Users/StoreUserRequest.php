<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize incoming request data
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Fix roles input (supports role[] or roles[])
            'roles' => $this->input('role', $this->input('roles', [])),
            'status' => filter_var($this->input('status'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

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
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],

            'company_id' => [
                'nullable',
                'exists:companies,id',
            ],

            // If you are using Spatie roles (array)
            'roles' => [
                'required',
            ],

            // BOOLEAN STATUS (FIXED)
            'status' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'roles.required' => 'Please select at least one role.',
            'status.boolean' => 'Status must be true or false (active/inactive switch).',
        ];
    }
}