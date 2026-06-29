<?php
declare(strict_types=1);

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
            'status' => ['nullable', 'boolean'],
            'product_description' => ['required', 'string'],
            'drawing_number' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'connection_id' => ['nullable', 'exists:connections,id'],
            // ================= SPECIFICATIONS =================
            'weight' => ['nullable', 'string'],
            'width' => ['nullable', 'string'],
            'volume' => ['nullable', 'string'],
            'teeth' => ['nullable', 'string'],
            'pin_hole' => ['nullable', 'string'],
            'machine_class' => ['nullable', 'string'],
            'material' => ['nullable', 'string', 'max:255'],
            'thickness' => ['nullable', 'string'],
            'reach' => ['nullable', 'string'],
            'machine_weight' => ['nullable', 'string'],
            'hinges' => ['nullable', 'string', 'max:255'],
            'stick_width' => ['nullable', 'string'],
            'pin_center' => ['nullable', 'string'],
            'cutting_edge_thickness' => ['nullable', 'string'],
            'ddp_price' => ['nullable', 'string'],
            'product_feature_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'product_gallery_images' => ['nullable', 'array'],
            'product_gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],

            'product_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'product_feature_image_temp' => ['nullable', 'string'],
            'product_gallery_images_temp' => ['nullable', 'string'],
            'product_pdf_temp' => ['nullable', 'string'],
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

            'weight.string' => 'Weight must be a valid value.',
            'width.string' => 'Width must be a valid value.',
            'volume.string' => 'Volume must be a valid value.',
            'teeth.string' => 'Teeth must be a valid value.',
            'pin_hole.string' => 'Pin hole must be a valid value.',
            'machine_class.string' => 'Machine class must be a valid value.',
            'thickness.string' => 'Thickness must be a valid value.',
            'reach.string' => 'Reach must be a valid value.',
            'machine_weight.string' => 'Machine weight must be a valid value.',
            'stick_width.string' => 'Stick width must be a valid value.',
            'pin_center.string' => 'Pin center must be a valid value.',
            'cutting_edge_thickness.string' => 'Cutting edge thickness must be a valid value.',
            'ddp_price.string' => 'DDP price must be a valid value.',

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