<?php

namespace App\Listeners;

use App\Events\ProductPriceHierarchyProcessing;
use App\Services\ProductPricingService;

class ResolveProductPriceHierarchy
{
    public function __construct(
        protected ProductPricingService $productPricingService
    ) {
    }

    public function handle(ProductPriceHierarchyProcessing $event): void
    {
        $productId = $event->displayData['product_id'];
        $mainPrice = (float) $event->displayData['main_price'];
        $users = $event->displayData['users'];

        foreach ($users as $wholesaler) {
            // Wholesaler
            $wholesalerPrice = $this->saveUserPrice(
                $wholesaler,
                $productId,
                $mainPrice
            );
            foreach ($wholesaler['children'] ?? [] as $reseller) {

                // Reseller
                $resellerPrice = $this->saveUserPrice(
                    $reseller,
                    $productId,
                    $wholesalerPrice
                );

                foreach ($reseller['children'] ?? [] as $customer) {

                    // Customer
                    $this->saveUserPrice(
                        $customer,
                        $productId,
                        $resellerPrice
                    );
                }
            }
        }
    }

    private function saveUserPrice(
        array $user,
        string $productId,
        float $parentPrice
    ): float {
        $priceData = $this->productPricingService->calculatePrice(
            $parentPrice,
            $user['marginType'],
            $user['user_margin_price']
        );

        $payload = [
            'product_id' => $productId,
            'user_id' => $user['user_id'],
            'type' => $user['role_name'],
            'base_price' => $priceData['base_price'],
            'margin' => $user['user_margin_price'],
            'final_price' => $priceData['final_price'],
        ];

        $this->productPricingService
            ->updateOrCreateProductPrice($payload);

        return (float) $priceData['final_price'];
    }
}