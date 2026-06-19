<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Models\Subcategory;
use App\Services\SubcategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function __construct(protected SubcategoryService $subcategoryService) {}

    public function index(): View
    {
        $this->authorize('viewAny', Subcategory::class);

        $subcategories = $this->subcategoryService->paginate(10);

        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create(): View
    {
        $this->authorize('create', Subcategory::class);

        $categories = $this->subcategoryService->getAll();

        return view('admin.subcategories.form', compact('categories'));
    }

    public function store(StoreSubcategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Subcategory::class);

        $this->subcategoryService->create($request->validated());

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit(string $id): View
    {
        $this->authorize('update', Subcategory::class);

        $subcategory = $this->subcategoryService->findById($id);
        $categories = $this->subcategoryService->getAll();

        return view('admin.subcategories.form', compact('subcategory', 'categories'));
    }

    public function update(UpdateSubcategoryRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update', Subcategory::class);

        $this->subcategoryService->update($id, $request->validated());

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->authorize('delete', Subcategory::class);

        $this->subcategoryService->delete($id);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
