<?php
declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Repositories\ProductPricingRepository;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductPriceManageController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected ProductPricingService $productPricingService,
        protected ProductPricingRepository $productPricingRepository,
    ) {}

    public function updateMargin(Request $request, string $productId): RedirectResponse
    {
        $request->validate([
            'margin' => 'required|numeric|min:0|max:100',
        ]);

        $product = $this->productService->findById($productId);

        $price = $this->productPricingRepository->findExisting(
            $product->id,
            auth()->id(),
            'wholesale_purchase'
        );

        if (!$price) {
            return redirect()->back()->with('error', 'No wholesale purchase price found for this product.');
        }

        $this->productPricingService->update($price->id, [
            'margin' => $request->margin,
        ]);

        return redirect()->back()->with('success', 'Margin updated successfully.');
    }
}
