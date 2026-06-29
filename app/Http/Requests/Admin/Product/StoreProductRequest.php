<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('product.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'product_title' => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:255', 'unique:products,product_code'],
            'drawing_number' => ['nullable', 'string', 'max:255'],
            'product_description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
            'internal_notes' => ['nullable', 'string'],
            'ddp_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'machine_class' => ['nullable', 'numeric'],
            'machine_weight' => ['nullable', 'numeric', 'min:0'],
            'hinges' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'volume' => ['nullable', 'numeric', 'min:0'],
            'cutting_edge_thickness' => ['nullable', 'numeric', 'min:0'],
            'teeth' => ['nullable', 'numeric', 'min:0'],
            'stick_width' => ['nullable', 'numeric', 'min:0'],
            'pin_center' => ['nullable', 'numeric', 'min:0'],
            'pin_hole' => ['nullable', 'numeric', 'min:0'],
            'thickness' => ['nullable', 'numeric', 'min:0'],
            'reach' => ['nullable', 'numeric', 'min:0'],
            'material' => ['nullable', 'string', 'max:255'],
            'connection_id' => ['required', 'exists:connections,id'],
            'product_feature_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'product_gallery_images' => ['nullable', 'array'],
            'product_gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'product_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'product_feature_image_temp' => ['nullable', 'string'],
            'product_gallery_images_temp' => ['nullable', 'string'],
            'product_pdf_temp' => ['nullable', 'string'],
            'product_prices' => ['nullable', 'array'],
            'product_prices.*.user_id' => ['required', 'string', 'exists:users,id'],
            'product_prices.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_title.required' => 'Please enter the product title.',
            'product_code.required' => 'Please enter the product code.',
            'product_code.unique' => 'This product code already exists.',
            'category_id.required' => 'Please select a category.',
            'subcategory_id.required' => 'Please select a subcategory.',
            'product_description.required' => 'Please enter the product description.',
            'product_feature_image.image' => 'Feature image must be an image file.',
            'product_gallery_images.*.image' => 'Each gallery file must be an image.',
            'product_pdf.mimes' => 'Only PDF files are allowed.',
        ];
    }
}
