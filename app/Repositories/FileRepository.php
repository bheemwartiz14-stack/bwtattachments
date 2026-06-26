<?php

namespace App\Repositories;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileRepository
{
    public function __construct(
        protected Media $mediaModel,
    ) {}

    public function findMediaById(string $id): ?Media
    {
        return $this->mediaModel->find($id);
    }

    public function deleteMedia(Media $media): bool
    {
        return (bool) $media->delete();
    }
}
