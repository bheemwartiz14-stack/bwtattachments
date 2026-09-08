<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'extensions:jpeg,png,jpg,webp,gif,pdf,ai,eps,svg,cdr,dxf,dwg', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.extensions' => 'Only JPEG, PNG, WebP, GIF, PDF and vector files (AI, EPS, SVG, CDR, DXF, DWG) are allowed.',
            'file.max' => 'File must not exceed 10MB.',
        ];
    }
}
