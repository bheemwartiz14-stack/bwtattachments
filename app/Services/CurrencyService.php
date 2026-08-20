<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    public function convert(float $amount, string $from, string $to): ?float
    {
        $rate = $this->rate($from, $to);

        if ($rate === null) {
            return null;
        }

        return round($amount * $rate, (int) config('currency.money_precision', 2));
    }

    public function rate(string $from, string $to): ?float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return 1.0;
        }

        $cacheKey = 'currency_rate_' . $from . '_' . $to;

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($from, $to) {
            return $this->fetchRate($from, $to);
        });
    }

    protected function fetchRate(string $from, string $to): ?float
    {
        try {
            $response = Http::timeout(8)
                ->get(config('currency.api_url') . '/latest', [
                    'base' => $from,
                    'symbols' => $to,
                ]);

            if (!$response->successful()) {
                return null;
            }

            $rate = $response->json('rates.' . $to);

            return is_numeric($rate) ? (float) $rate : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
