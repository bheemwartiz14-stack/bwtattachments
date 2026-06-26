<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('product.update') ?? false;
    }

    public function rules(): array
    {
        return [
            // ================= BASIC INFO =================
            'product_title' => ['required', 'string', 'max:255'],

            'product_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_code')
                    ->ignore($this->route('product')),
            ],

            'product_description' => ['required', 'string'],
            'drawing_number' => ['nullable', 'string', 'max:255'],

            'category_id' => ['nullable', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'connection_id' => ['nullable', 'exists:connections,id'],

            // ================= SPECIFICATIONS =================
            'weight' => ['nullable', 'numeric'],
            'width' => ['nullable', 'numeric'],
            'volume' => ['nullable', 'numeric'],
            'teeth' => ['nullable', 'numeric'],
            'pin_hole' => ['nullable', 'numeric'],
            'machine_class' => ['nullable', 'numeric'],
            'material' => ['nullable', 'string', 'max:255'],
            'thickness' => ['nullable', 'numeric'],
            'reach' => ['nullable', 'numeric'],
            'machine_weight' => ['nullable', 'numeric'],
            'hinges' => ['nullable', 'string', 'max:255'],
            'stick_width' => ['nullable', 'numeric'],
            'pin_center' => ['nullable', 'numeric'],
            'pin_hole' => ['nullable', 'numeric'],
            'thickness' => ['nullable', 'numeric'],
            'reach' => ['nullable', 'numeric'],
            'cutting_edge_thickness' => ['nullable', 'numeric'],
            'ddp_price' => ['nullable', 'numeric'],
            'product_feature_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'product_gallery_images' => ['nullable', 'array'],
            'product_gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],

            'product_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'product_prices' => ['nullable', 'array'],
            'product_prices.*.user_id' => ['required', 'string', 'exists:users,id'],
            'product_prices.*.price' => ['required', 'numeric', 'min:0'],

            'internal_notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_title.required' => 'Please enter the product title.',
            'product_code.required' => 'Please enter the product code.',
            'product_code.unique' => 'This product code already exists.',
            'product_description.required' => 'Please enter the product description.',

            'category_id.exists' => 'The selected category is invalid.',
            'subcategory_id.exists' => 'The selected subcategory is invalid.',
            'connection_id.exists' => 'The selected connection type is invalid.',

            'weight.numeric' => 'Weight must be a valid number.',
            'width.numeric' => 'Width must be a valid number.',
            'volume.numeric' => 'Volume must be a valid number.',
            'number_of_teeth.numeric' => 'Number of teeth must be a valid number.',
            'pin_hole.numeric' => 'Pin hole must be a valid number.',
            'machine_class.numeric' => 'Machine class must be a valid number.',
            'thickness.numeric' => 'Thickness must be a valid number.',
            'reach.numeric' => 'Reach must be a valid number.',
            'machine_weight.numeric' => 'Machine weight must be a valid number.',
            'stick_width.numeric' => 'Stick width must be a valid number.',
            'pin_center.numeric' => 'Pin center must be a valid number.',
            'cutting_edge_thickness.numeric' => 'Cutting edge thickness must be a valid number.',
            'ddp_price.numeric' => 'DDP price must be a valid number.',

            'product_feature_image.image' => 'Feature image must be an image file.',
            'product_gallery_images.*.image' => 'Each gallery image must be a valid image.',
            'product_pdf.mimes' => 'Only PDF files are allowed.',
            'product_pdf.max' => 'The PDF file may not be greater than 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_title' => 'product title',
            'product_code' => 'product code',
            'product_description' => 'product description',
            'category_id' => 'category',
            'subcategory_id' => 'subcategory',
            'connection_id' => 'connection type',
            'machine_weight' => 'machine weight',
            'cutting_edge_thickness' => 'cutting edge thickness',
            'stick_width' => 'stick width',
            'pin_center' => 'pin center',
            'pin_hole' => 'pin hole',
            'ddp_price' => 'DDP price',
            'product_feature_image' => 'feature image',
            'product_gallery_images' => 'gallery images',
            'product_pdf' => 'PDF file',
        ];
    }
}