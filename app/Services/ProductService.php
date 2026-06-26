<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductMediaService $productMediaService,
    ) {}

    public function getAll(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage, $filters);
    }

    public function findById(string $id): \App\Models\Product
    {
        return $this->productRepository->findById($id);
    }

    public function create(array $data): \App\Models\Product
    {
        return DB::transaction(function () use ($data) {
            $media = $this->extractMedia($data);
            $media = $this->resolveTempMedia($data, $media);
            $this->unsetMediaKeys($data);
            $product = $this->productRepository->create($data);
            $this->handleMedia($product, $media);
            $this->cleanupTemp($media);
            return $product->load('media');
        });
    }

    public function update(string $id, array $data): \App\Models\Product
    {
        return DB::transaction(function () use ($id, $data) {
            $media = $this->extractMedia($data);
            $media = $this->resolveTempMedia($data, $media);
            $deletedGallery = $data['deleted_gallery'] ?? null;
            $deleteFeature = ($data['product_feature_image_deleted'] ?? null) === '1';
            $deletePdf = ($data['product_pdf_deleted'] ?? null) === '1';
            $this->unsetMediaKeys($data);
            $product = $this->productRepository->update($id, $data);
            if ($deleteFeature) {
                $product->clearMediaCollection('images');
            }
            if ($deletePdf) {
                $product->clearMediaCollection('pdfs');
            }
            $this->handleMedia($product, $media);
            if ($deletedGallery) {
                $ids = json_decode($deletedGallery, true);
                if (is_array($ids) && !empty($ids)) {
                    $this->productMediaService->removeGalleryImages($product, $ids);
                }
            }
            $this->cleanupTemp($media);
            return $product->load('media');
        });
    }

    public function delete(string $id): bool
    {
        $product = $this->productRepository->findById($id);

        if ($product->quotationItems()->exists()) {
            return false;
        }

        return DB::transaction(function () use ($product) {
            return (bool) $product->delete();
        });
    }

    public function search(string $term): Collection
    {
        return $this->productRepository->search($term);
    }

    public function findByCategory(string $categoryId): Collection
    {
        return $this->productRepository->findByCategory($categoryId);
    }

    public function findBySubcategory(string $subcategoryId): Collection
    {
        return $this->productRepository->findBySubcategory($subcategoryId);
    }

    public function findByConnection(string $connectionId): Collection
    {
        return $this->productRepository->findByConnection($connectionId);
    }

    private function extractMedia(array &$data): array
    {
        return [
            'feature' => $data['product_feature_image'] ?? null,
            'gallery' => $data['product_gallery_images'] ?? [],
            'pdf' => $data['product_pdf'] ?? null,
        ];
    }

    private function handleMedia(\App\Models\Product $product, array $media): void
    {
        if ($media['feature'] instanceof UploadedFile) {
            $this->productMediaService->attachFeatureImage($product, $media['feature']);
        }

        if (!empty($media['gallery'])) {
            $this->productMediaService->attachGalleryImages($product, $media['gallery']);
        }

        if ($media['pdf'] instanceof UploadedFile) {
            $this->productMediaService->attachPdf($product, $media['pdf']);
        }
    }

    private function resolveTempMedia(array &$data, array $media): array
    {
        $tempFeature = $this->resolveTempFile($data['product_feature_image_temp'] ?? null);
        $tempPdf = $this->resolveTempFile($data['product_pdf_temp'] ?? null);
        $tempGallery = $this->resolveTempGallery($data['product_gallery_images_temp'] ?? null);

        if ($tempFeature) {
            $media['feature'] = $tempFeature;
        }
        if ($tempPdf) {
            $media['pdf'] = $tempPdf;
        }
        if (!empty($tempGallery)) {
            $media['gallery'] = $tempGallery;
        }

        return $media;
    }

    private function unsetMediaKeys(array &$data): void
    {
        unset(
            $data['product_feature_image'],
            $data['product_pdf'],
            $data['product_gallery_images'],
            $data['deleted_gallery'],
            $data['product_feature_image_temp'],
            $data['product_pdf_temp'],
            $data['product_gallery_images_temp'],
            $data['product_feature_image_deleted'],
            $data['product_pdf_deleted'],
        );
    }

    private function resolveTempFile(?string $jsonData): ?UploadedFile
    {
        if (!$jsonData) return null;
        $parsed = json_decode($jsonData, true);
        if (!$parsed || !isset($parsed['token'])) return null;

        $token = $parsed['token'];
        $disk = Storage::disk('public');
        $files = $disk->files('temp/' . $token);

        if (empty($files)) return null;

        $fullPath = $disk->path($files[0]);
        $originalName = $parsed['name'] ?? basename($files[0]);

        return new UploadedFile($fullPath, $originalName, $disk->mimeType($files[0]), null, true);
    }

    private function resolveTempGallery(?string $jsonData): array
    {
        if (!$jsonData) return [];
        $items = json_decode($jsonData, true);
        if (!is_array($items)) return [];

        $files = [];
        foreach ($items as $item) {
            $file = $this->resolveTempFile(json_encode($item));
            if ($file) {
                $files[] = $file;
            }
        }
        return $files;
    }

    private function cleanupTemp(array $media): void
    {
        $files = [];

        if ($media['feature'] instanceof UploadedFile) {
            $files[] = $media['feature']->getPathname();
        }

        if ($media['pdf'] instanceof UploadedFile) {
            $files[] = $media['pdf']->getPathname();
        }

        if (!empty($media['gallery'])) {
            foreach ($media['gallery'] as $file) {
                if ($file instanceof UploadedFile) {
                    $files[] = $file->getPathname();
                }
            }
        }

        $disk = Storage::disk('public');
        foreach ($files as $path) {
            $relative = $disk->files(dirname(str_replace($disk->path(''), '', $path)));
            if (!empty($relative)) {
                $dir = dirname($relative[0]);
                $disk->deleteDirectory($dir);
            }
        }
    }
}
