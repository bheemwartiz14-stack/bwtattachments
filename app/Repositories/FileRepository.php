<?php
declare(strict_types=1);

namespace App\Repositories;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileRepository
{
    public function __construct(
        protected Media $model,
    ) {
    }

    /**
     * Find media by ID.
     */
    public function findById(string|int $id): ?Media
    {
        return $this->model
            ->query()
            ->find($id);
    }

    /**
     * Delete media.
     */
    public function delete(Media $media): bool
    {
        return (bool) $media->delete();
    }
}