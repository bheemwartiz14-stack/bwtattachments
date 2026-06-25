<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * List Categories
     */
    public function index(): View
    {
        $categories = $this->categoryService->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Create Form
     */
    public function create(): View
    {
        return view('admin.categories.form');
    }

    /**
     * Store Category
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Edit Form
     */
    public function edit(string $id): View
    {
        $category = $this->categoryService->findById($id);

        abort_if(!$category, 404);

        return view('admin.categories.form', compact('category'));
    }

    /**
     * Update Category
     */
    public function update(UpdateCategoryRequest $request, string $id): RedirectResponse
    {
        $this->categoryService->update($id, $request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete Category
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->categoryService->delete($id);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Get Subcategories (AJAX)
     */
    public function getSubcategories(string $categoryId)
    {
        $subcategories = $this->categoryService->getSubcategories($categoryId);

        return response()->json([
            'data' => $subcategories,
        ]);
    }
}