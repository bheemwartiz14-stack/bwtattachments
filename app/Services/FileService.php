<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;

class FileService
{
    public function __construct(
        protected FileRepository $fileRepository,
    ) {}

    public function storeTemp(UploadedFile $file): array
    {
        $token = Str::random(32);

        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            $image = Image::decodeBinary($file->get())->cover(1200, 800, 'center');
            $filename = Str::uuid() . '.webp';
            $path = "temp/{$token}/{$filename}";
            $encodedImage = (string) $image->encodeUsingFormat(Format::WEBP, 85);
            Storage::disk('public')->put($path, $encodedImage);
            return [
                'token' => $token,
                'name' => $filename,
                'size' => strlen($encodedImage),
                'url' => Storage::disk('public')->url($path),
                'mime_type' => 'image/webp',
                'extension' => 'webp',
                'path' => $path,
            ];
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("temp/{$token}", $filename, 'public');
        return [
            'token' => $token,
            'name' => $filename,
            'size' => $file->getSize(),
            'url' => Storage::disk('public')->url($path),
            'mime_type' => $mimeType,
            'extension' => $file->getClientOriginalExtension(),
            'path' => $path,
        ];
    }

    public function deleteMedia(string $id): bool
    {
        $media = $this->fileRepository->findById($id);

        if (!$media) {
            return false;
        }

        return $this->fileRepository->delete($media);
    }
}
