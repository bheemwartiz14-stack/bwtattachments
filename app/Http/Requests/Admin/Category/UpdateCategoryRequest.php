<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('category.update') ?? false;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $categoryId,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique' => 'This category name already exists.',
            'name.max' => 'Category name must not exceed 255 characters.',
        ];
    }
}