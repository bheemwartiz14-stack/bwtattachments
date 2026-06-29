<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\Subcategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubcategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('subcategory.update') ?? false;
    }

    public function rules(): array
    {
        $subcategoryId = $this->route('subcategory');

        return [
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:subcategories,name,' . $subcategoryId,
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