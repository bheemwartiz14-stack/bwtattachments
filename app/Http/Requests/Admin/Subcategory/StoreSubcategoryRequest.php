<?php

namespace App\Http\Requests\Admin\Subcategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubcategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('subcategory.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:subcategories,name',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category is invalid.',

            'name.required' => 'Subcategory name is required.',
            'name.unique' => 'This subcategory already exists.',
            'name.max' => 'Subcategory name must not exceed 255 characters.',
        ];
    }
}