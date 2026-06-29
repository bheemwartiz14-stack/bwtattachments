<?php
declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected ProductService $productService
    ) {}

    public function index(): View
    {
        $categories = $this->categoryService->getAll();

        return view('public.categories.index', compact('categories'));
    }

    public function show(string $id): View
    {
        $category = $this->categoryService->findById($id);
        $products = $this->productService->findByCategory($id);

        return view('public.categories.show', compact('category', 'products'));
    }
}
