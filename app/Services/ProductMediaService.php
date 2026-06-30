<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;
use LaravelAt\ImageSanitize\ImageSanitize;

readonly class ProductMediaService
{
    public function __construct(
        private ImageSanitize $imageSanitize,
    ) {}

    public function attachFeatureImage(Model $product, UploadedFile $file): void
    {
        $product
            ->addMedia($this->processImage($file))
            ->preservingOriginal()
            ->toMediaCollection('images', 'public');
    }

    public function attachGalleryImages(Model $product, array $files): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $product
                    ->addMedia($this->processImage($file))
                    ->preservingOriginal()
                    ->toMediaCollection('gallery', 'public');
            }
        }
    }

    public function attachPdf(Model $product, UploadedFile $file): void
    {
        $product
            ->addMedia($file)
            ->preservingOriginal()
            ->toMediaCollection('pdfs', 'public');
    }

    private function processImage(UploadedFile $file): UploadedFile
    {
        $sanitized = $this->sanitizeImage($file);
        $image = Image::decode($sanitized)->cover(1200, 800, 'center');
        $tempPath = tempnam(sys_get_temp_dir(), 'img_processed_') . '.webp';
        $encoded = (string) $image->encodeUsingFormat(Format::WEBP, 85);
        file_put_contents($tempPath, $encoded);
        return new UploadedFile($tempPath, Str::uuid() . '.webp', 'image/webp', null, true);
    }

    private function sanitizeImage(UploadedFile $file): UploadedFile
    {
        $content = file_get_contents($file->getRealPath());

        if (!$this->imageSanitize->detect($content)) {
            return $file;
        }
        $sanitized = $this->imageSanitize->sanitize($content);
        $tempPath = tempnam(sys_get_temp_dir(), 'img_sanitized_') . '.' . $file->getClientOriginalExtension();
        file_put_contents($tempPath, $sanitized->encoded);
        return new UploadedFile($tempPath, $file->getClientOriginalName(), $file->getMimeType(), null, true);
    }
}
