<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function __construct(
        protected FileRepository $fileRepository,
    ) {}

    public function storeTemp(UploadedFile $file): array
    {
        $token = Str::random(32);

        // Store original file as-is (no cover/webp conversion)
        // Preserve original name/extension/mime
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension()
            ?: $file->guessExtension()
            ?: 'bin';

        $filename = Str::uuid() . '.' . $extension;

        $path = $file->storeAs(
            "temp/{$token}",
            $filename,
            'public'
        );
        $fullPath = Storage::disk('public')->path($path);
        return [
            'token' => $token,
            'name' => $originalName,
            'size' => $file->getSize(),
            'url' => Storage::disk('public')->url($path),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'path' => $fullPath,
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

    public function copyFile(string $source, string $destination): string
    {
        Storage::disk('public')->put(
            $destination,
            file_get_contents($source)
        );

        return $destination;
    }

        public function storeFile(string $filename, string $content): string
        {
            Storage::disk('public')->put($filename, $content);

            return $filename;
        }
}
