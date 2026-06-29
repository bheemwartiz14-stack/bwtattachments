<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\Connection;

use Illuminate\Foundation\Http\FormRequest;

class StoreConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('connection.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:connections,name',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Connection name is required.',
            'name.unique' => 'This connection already exists.',
            'name.max' => 'Connection name must not exceed 255 characters.',
        ];
    }
}