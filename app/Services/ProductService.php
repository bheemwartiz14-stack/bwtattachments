<?php

namespace App\Services;

use App\Models\ProductImage;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    public function getAll(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage);
    }

    public function findById(int $id): Model
    {
        return $this->productRepository->findById($id);
    }

    public function create(array $data): Model
    {
        if (isset($data['pdf_file']) && $data['pdf_file'] instanceof UploadedFile) {
            $data['pdf_file'] = $data['pdf_file']->store('products', 'public');
        }

        $product = $this->productRepository->create($data);

        if (isset($data['product_images']) && is_array($data['product_images'])) {
            foreach ($data['product_images'] as $sortOrder => $image) {
                if ($image instanceof UploadedFile) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'sort_order' => $sortOrder,
                    ]);
                }
            }
        }

        return $product;
    }

    public function update(int $id, array $data): Model
    {
        if (isset($data['pdf_file']) && $data['pdf_file'] instanceof UploadedFile) {
            $data['pdf_file'] = $data['pdf_file']->store('products', 'public');
        }

        $product = $this->productRepository->update($id, $data);

        if (isset($data['product_images']) && is_array($data['product_images'])) {
            foreach ($data['product_images'] as $sortOrder => $image) {
                if ($image instanceof UploadedFile) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'sort_order' => $sortOrder,
                    ]);
                }
            }
        }

        return $product;
    }

    public function delete(int $id): bool
    {
        return $this->productRepository->delete($id);
    }

    public function restore(int $id): Model
    {
        return $this->productRepository->restore($id);
    }

    public function search(string $term): Collection
    {
        return $this->productRepository->search($term);
    }

    public function findByCategory(int $categoryId): Collection
    {
        return $this->productRepository->findByCategory($categoryId);
    }

    public function findBySubcategory(int $subcategoryId): Collection
    {
        return $this->productRepository->findBySubcategory($subcategoryId);
    }

    public function findByConnection(int $connectionId): Collection
    {
        return $this->productRepository->findByConnection($connectionId);
    }
}
