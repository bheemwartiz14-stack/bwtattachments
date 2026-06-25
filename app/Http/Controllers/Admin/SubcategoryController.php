<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subcategory\StoreSubcategoryRequest;
use App\Http\Requests\Admin\Subcategory\UpdateSubcategoryRequest;
use App\Services\SubcategoryService;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function __construct(
        protected SubcategoryService $subcategoryService,
        protected CategoryService $categoryService
    ) {}

    /**
     * List Subcategories
     */
    public function index(): View
    {
        $subcategories = $this->subcategoryService->paginate(10);

        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Create Form
     */
    public function create(): View
    {
        $categories = $this->categoryService->getAll();
        
        return view('admin.subcategories.form', compact('categories'));
    }

    /**
     * Store Subcategory
     */
    public function store(StoreSubcategoryRequest $request): RedirectResponse
    {
        $this->subcategoryService->create($request->validated());

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory created successfully.');
    }

    /**
     * Edit Form
     */
    public function edit(string $id): View
    {
        $subcategory = $this->subcategoryService->findById($id);

        abort_if(!$subcategory, 404);

        $categories = $this->categoryService->getAll();

        return view('admin.subcategories.form', compact('subcategory', 'categories'));
    }

    /**
     * Update Subcategory
     */
    public function update(UpdateSubcategoryRequest $request, string $id): RedirectResponse
    {
        $this->subcategoryService->update($id, $request->validated());

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Delete Subcategory
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->subcategoryService->delete($id);

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
    }
}