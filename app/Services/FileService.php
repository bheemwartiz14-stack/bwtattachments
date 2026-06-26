<?php

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
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs('temp/' . $token, $originalName, 'public');
        $mimeType = $file->getMimeType();

        return [
            'token' => $token,
            'name' => $originalName,
            'size' => $file->getSize(),
            'url' => Storage::disk('public')->url($path),
            'mime_type' => $mimeType,
            'extension' => $extension,
        ];
    }

    public function deleteMedia(string $id): bool
    {
        $media = $this->fileRepository->findMediaById($id);
        if (!$media) return false;

        return $this->fileRepository->deleteMedia($media);
    }
}
