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

    public function getTransactionVatCountryInfo(User $buyer, User  $seller){
         if ( $buyer->country_code !== $seller->country_code ) {
            return [
                'iso_code' => '',
                'standard_vat_rate' => 0,
            ];
        }else{
            $vatCountry = VatRate::query()->where('iso_code', $buyer->country_code)->first();
            return [
                'iso_code' => $vatCountry->iso_code,
                'standard_vat_rate' => $vatCountry->standard_vat_rate,
                ];
        }
    }
}
