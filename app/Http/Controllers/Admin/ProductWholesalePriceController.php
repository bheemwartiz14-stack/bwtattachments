<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductPrice\StoreProductPriceRequest;
use App\Http\Requests\Admin\ProductPrice\UpdateProductPriceRequest;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductWholesalePriceController extends Controller
{
    public function __construct(
        protected ProductPricingService $productPricingService,
        protected ProductService $productService,
        protected UserService $userService,
    ) {}

    public function index(Request $request): View
    {
        return view('admin.product-pricing.index', [
            'prices' => $this->productPricingService->paginate(
                (int) $request->query('per_page', 15),
                $request->only(['search', 'product_id', 'user_id', 'type'])
            ),
        ]);
    }

    public function create(): View
    {
        return view('admin.product-pricing.form', [
            'price' => null,
            'products' => $this->productPricingService->getProductsForSelect(),
            'wholesaleUsers' => $this->productPricingService->getWholesaleUsersForSelect(),
        ]);
    }

    public function store(StoreProductPriceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['type'] = 'wholesale_purchase';
        $data['assigned_by'] = auth()->id();

        $this->productPricingService->store($data);

        return redirect()->route('admin.product-pricing.index')
            ->with('success', 'Wholesale purchase price created successfully.');
    }

    public function show(string $id): View
    {
        return view('admin.product-pricing.show', [
            'price' => $this->productPricingService->findById($id),
        ]);
    }

    public function edit(string $id): View
    {
        $price = $this->productPricingService->findById($id);
        return view('admin.product-pricing.form', [
            'price' => $price,
            'products' => $this->productPricingService->getProductsForSelect(),
            'wholesaleUsers' => $this->productPricingService->getWholesaleUsersForSelect(),
        ]);
    }

    public function update(UpdateProductPriceRequest $request, string $id): RedirectResponse
    {
        $this->productPricingService->update($id, $request->validated());
        return redirect()->route('admin.product-pricing.index')
            ->with('success', 'Wholesale price updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->productPricingService->delete($id);
        return redirect()->route('admin.product-pricing.index')
            ->with('success', 'Wholesale price deleted successfully.');
    }

    public function getProductPreview(string $id): JsonResponse
    {
        $product = $this->productService->findById($id);
        return response()->json([
            'title' => $product->product_title,
            'code' => $product->product_code,
            'ddp_price' => $product->ddp_price,
            'image' => $product->getFirstMediaUrl('images'),
        ]);
    }

    public function getUserPreview(string $id): JsonResponse
    {
        $user = $this->userService->findById($id);
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
