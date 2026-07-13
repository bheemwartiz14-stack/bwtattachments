<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Term;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class TermService
{
    public function getAll(): Collection
    {
        return Term::query()
            ->with('createdBy')
            ->latest()
            ->get();
    }

    public function getActive(): Collection
    {
        return Term::query()
            ->where('is_active', true)
            ->with('createdBy')
            ->latest()
            ->get();
    }

    public function findById(string $id): Term
    {
        return Term::query()
            ->with('createdBy', 'media')
            ->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $file = null): Term
    {
        $term = Term::create($data);

        if ($file) {
            $term->addMedia($file)
                ->toMediaCollection('file');
        }

        return $term;
    }

    public function update(string $id, array $data, ?UploadedFile $file = null): Term
    {
        $term = $this->findById($id);
        $term->update($data);

        if ($file) {
            $term->clearMediaCollection('file');
            $term->addMedia($file)
                ->toMediaCollection('file');
        }

        return $term->refresh();
    }

    public function delete(string $id): bool
    {
        $term = $this->findById($id);
        $term->clearMediaCollection('file');
        return $term->delete();
    }
}
