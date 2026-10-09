<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\CurrencyService;
use App\Services\ProductService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('products:update-prices-by-role')]
#[Description('Fetch live currency rates from the converter API and update product prices')]
class UpdateProductPricesByRole extends Command
{
    public function handle()
    {
        $productService = app(ProductService::class);
        $currencyService = app(CurrencyService::class);

        // Stop early if the rate is not available
        $rate = $currencyService->rate('CNY', 'EUR');

        if ($rate === null) {
            $this->error('Could not get the CNY to EUR rate.');
            return self::FAILURE;
        }

        $this->info("Rate used: 1 CNY = {$rate} EUR");

        $updated = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar(Product::count());
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | %message%');
        $bar->setMessage('-');
        $bar->start();

        Product::query()->chunkById(200, function ($products) use (&$updated, &$skipped, $currencyService, $productService, $bar) {
            foreach ($products as $product) {
                $name = (string) $product->name;

                // Progress bar shows only the product name
                $bar->setMessage($name);

                $rmb = (float) ($product->ddp_price_rmb ?? 0);

                if ($rmb <= 0) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $converted = $currencyService->convert($rmb, 'CNY', 'EUR');

                if ($converted === null) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Read the old price BEFORE saving the new one
                $before = (float) $product->ddp_price;

                $product->ddp_price = $converted;
                $product->save();

                $productService->AutomaticUpdateProductPrice($product);

                // Print one line above the progress bar
                $bar->clear();
                $this->line(sprintf(
                    '%s | %s CNY | before: %s EUR → after: %s EUR',
                    $name,
                    number_format($rmb, 2),
                    number_format($before, 2),
                    number_format($converted, 2)
                ));
                $bar->display();

                $updated++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("Products updated: {$updated}, skipped: {$skipped}");

        return self::SUCCESS;
    }
}
