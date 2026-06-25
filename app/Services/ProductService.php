<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
            unset($data['product_feature_image'], $data['product_pdf'],$data['product_gallery_images']);
            $product = $this->productRepository->create($data);
            $this->handleMedia($product, $media);
            
            return $product->load('media');
        });
    }

    public function update(string $id, array $data): \App\Models\Product
    {
        return DB::transaction(function () use ($id, $data) {
            $media = $this->extractMedia($data);
            unset($data['product_feature_image'], $data['product_pdf'],$data['product_gallery_images']);
            $product = $this->productRepository->update($id, $data);
            $this->handleMedia($product, $media);
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
}
