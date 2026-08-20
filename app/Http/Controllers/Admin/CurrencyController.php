<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function __construct(protected CurrencyService $currencyService)
    {
    }

    public function rate(string $from, string $to): JsonResponse
    {
        $rate = $this->currencyService->rate($from, $to);

        if ($rate === null) {
            return response()->json(['error' => 'Unable to fetch exchange rate.'], 422);
        }

        return response()->json([
            'from' => strtoupper($from),
            'to' => strtoupper($to),
            'rate' => $rate,
        ]);
    }
}
