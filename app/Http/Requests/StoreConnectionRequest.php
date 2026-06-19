<?php

namespace App\Http\Requests;

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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:connections'],
        ];
    }
}
