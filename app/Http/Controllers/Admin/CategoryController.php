<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = $this->categoryService->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.form');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Category::class);

        $this->categoryService->create($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(string $id): View
    {
        $this->authorize('update', Category::class);

        $category = $this->categoryService->findById($id);

        return view('admin.categories.form', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, string $id): RedirectResponse
    {
        $this->authorize('update', Category::class);

        $this->categoryService->update($id, $request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->authorize('delete', Category::class);

        $this->categoryService->delete($id);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

}
