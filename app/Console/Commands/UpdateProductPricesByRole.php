<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\CurrencyService;
use App\Services\ProductService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('products:update-prices-by-role')]
#[Description('Fetch live currency rates from the converter API and update product prices')]
class UpdateProductPricesByRole extends Command
{
    public function handle()
    {
        $log = Log::channel('price_update');

        $startedAt = now();

        $productService = app(ProductService::class);
        $currencyService = app(CurrencyService::class);

        // Stop early if the rate is not available
        $rate = $currencyService->rate('CNY', 'EUR');

        if ($rate === null) {
            $this->error('Could not get the CNY to EUR rate.');
            $log->error('Could not get the CNY to EUR rate. Run stopped at ' . now()->format('Y-m-d H:i:s'));
            return self::FAILURE;
        }

        $this->info("Rate used: 1 CNY = {$rate} EUR");
        $log->info('===== LOG START | ' . $startedAt->format('Y-m-d H:i:s') . " | Rate used: 1 CNY = {$rate} EUR =====");

        $updated = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar(Product::count());
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | %message%');
        $bar->setMessage('-');
        $bar->start();

        Product::query()->chunkById(200, function ($products) use (&$updated, &$skipped, $currencyService, $productService, $bar, $log) {
            foreach ($products as $product) {
                $name = (string) $product->name;

                // Progress bar shows only the product name
                $bar->setMessage($name);

                $rmb = (float) ($product->ddp_price_rmb ?? 0);

                if ($rmb <= 0) {
                    $skipped++;
                    $log->warning("#{$product->id} {$name} | skipped: no ddp_price_rmb");
                    $bar->advance();
                    continue;
                }

                $converted = $currencyService->convert($rmb, 'CNY', 'EUR');

                if ($converted === null) {
                    $skipped++;
                    $log->warning("#{$product->id} {$name} | skipped: conversion failed");
                    $bar->advance();
                    continue;
                }

                // Read the old price BEFORE saving the new one
                $before = (float) $product->ddp_price;

                $product->ddp_price = $converted;
                $product->save();

                $productService->AutomaticUpdateProductPrice($product);

                $line = sprintf(
                    '%s | %s CNY | before: %s EUR → after: %s EUR',
                    $name,
                    number_format($rmb, 2),
                    number_format($before, 2),
                    number_format($converted, 2)
                );

                // Write the same line to the log file (with the ID)
                $log->info("#{$product->id} {$line}");

                // Print one line above the progress bar
                $bar->clear();
                $this->line($line);
                $bar->display();

                $updated++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("Products updated: {$updated}, skipped: {$skipped}");

        $finishedAt = now();
        $seconds = $startedAt->diffInSeconds($finishedAt);

        $log->info(
            '===== LOG END | ' . $finishedAt->format('Y-m-d H:i:s')
            . " | updated: {$updated}, skipped: {$skipped} | duration: {$seconds}s ====="
        );

        return self::SUCCESS;
    }
}
