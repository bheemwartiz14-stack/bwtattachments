<?php

declare(strict_types=1);

namespace App\Services;
use App\Models\VatRate;
use App\Models\User;
class VatRateService
{
    /**
     * Get VAT rates as select options.
     *
     * @return array<string, string>
     */
    public function options(): array
    {
        return VatRate::query()->orderBy('country')->get()->toArray();
    }

    public function getTransactionVatCountryInfo(?User $buyer, ?User $seller): array
    {
        $default = [
            'iso_code' => '',
            'standard_vat_rate' => 0,
        ];

        $buyerCode = $buyer?->country_code;
        $sellerCode = $seller?->country_code;

        if (! $buyerCode || ! $sellerCode || $buyerCode !== $sellerCode) {
            return $default;
        }

        $vatCountry = VatRate::query()->where('iso_code', $buyerCode)->first();
        if (! $vatCountry) {
            return $default;
        }

        return [
            'iso_code' => $vatCountry->iso_code,
            'standard_vat_rate' => $vatCountry->standard_vat_rate,
        ];
    }
}
