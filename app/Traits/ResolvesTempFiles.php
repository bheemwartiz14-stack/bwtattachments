<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ResolvesTempFiles
{
    private function resolveTempFile(?string $jsonData): ?UploadedFile
    {
        if (!$jsonData) return null;

        $parsed = json_decode($jsonData, true);
        if (!$parsed || !isset($parsed['token'])) return null;

        $token = $parsed['token'];
        $disk = Storage::disk('public');
        $files = $disk->files('temp/' . $token);

        if (empty($files)) return null;

        return new UploadedFile(
            $disk->path($files[0]),
            $parsed['name'] ?? basename($files[0]),
            $disk->mimeType($files[0]),
            null,
            true
        );
    }

    private function resolveTempImage(array &$data, string $field): ?UploadedFile
    {
        $temp = $data[$field . '_temp'] ?? null;
        unset($data[$field . '_temp']);
        return $this->resolveTempFile($temp);
    }
}
