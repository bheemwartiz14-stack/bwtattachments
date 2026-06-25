<?php

namespace App\Http\Requests\Admin\Connection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('connection.update') ?? false;
    }

    public function rules(): array
    {
        $connectionId = $this->route('connection');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:connections,name,' . $connectionId,
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