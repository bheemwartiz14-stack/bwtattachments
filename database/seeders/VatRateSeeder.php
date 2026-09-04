<?php
declare(strict_types=1);

namespace Database\Seeders;
use App\Models\VatRate;
use Illuminate\Database\Seeder;

class VatRateSeeder extends Seeder
{
    public function run(): void
    {
        $vatRates = [
            [
                'country' => 'Austria',
                'iso_code' => 'AT',
                'standard_vat_rate' => 20.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Belgium',
                'iso_code' => 'BE',
                'standard_vat_rate' => 21.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Bulgaria',
                'iso_code' => 'BG',
                'standard_vat_rate' => 20.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Croatia',
                'iso_code' => 'HR',
                'standard_vat_rate' => 25.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Cyprus',
                'iso_code' => 'CY',
                'standard_vat_rate' => 19.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Czechia',
                'iso_code' => 'CZ',
                'standard_vat_rate' => 21.00,
                'eu_status' => 'EU',
                'currency' => 'CZK',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Denmark',
                'iso_code' => 'DK',
                'standard_vat_rate' => 25.00,
                'eu_status' => 'EU',
                'currency' => 'DKK',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Estonia',
                'iso_code' => 'EE',
                'standard_vat_rate' => 24.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Finland',
                'iso_code' => 'FI',
                'standard_vat_rate' => 25.50,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'France',
                'iso_code' => 'FR',
                'standard_vat_rate' => 20.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Germany',
                'iso_code' => 'DE',
                'standard_vat_rate' => 19.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Greece',
                'iso_code' => 'EL',
                'standard_vat_rate' => 24.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Hungary',
                'iso_code' => 'HU',
                'standard_vat_rate' => 27.00,
                'eu_status' => 'EU',
                'currency' => 'HUF',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Ireland',
                'iso_code' => 'IE',
                'standard_vat_rate' => 23.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Italy',
                'iso_code' => 'IT',
                'standard_vat_rate' => 22.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Latvia',
                'iso_code' => 'LV',
                'standard_vat_rate' => 21.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Lithuania',
                'iso_code' => 'LT',
                'standard_vat_rate' => 21.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Luxembourg',
                'iso_code' => 'LU',
                'standard_vat_rate' => 17.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Malta',
                'iso_code' => 'MT',
                'standard_vat_rate' => 18.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Netherlands',
                'iso_code' => 'NL',
                'standard_vat_rate' => 21.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Poland',
                'iso_code' => 'PL',
                'standard_vat_rate' => 23.00,
                'eu_status' => 'EU',
                'currency' => 'PLN',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Portugal',
                'iso_code' => 'PT',
                'standard_vat_rate' => 23.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Romania',
                'iso_code' => 'RO',
                'standard_vat_rate' => 21.00,
                'eu_status' => 'EU',
                'currency' => 'RON',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Slovakia',
                'iso_code' => 'SK',
                'standard_vat_rate' => 23.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Slovenia',
                'iso_code' => 'SI',
                'standard_vat_rate' => 22.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Spain',
                'iso_code' => 'ES',
                'standard_vat_rate' => 21.00,
                'eu_status' => 'EU',
                'currency' => 'EUR',
                'b2b_reverse_charge' => true,
            ],
            [
                'country' => 'Sweden',
                'iso_code' => 'SE',
                'standard_vat_rate' => 25.00,
                'eu_status' => 'EU',
                'currency' => 'SEK',
                'b2b_reverse_charge' => true,
            ],
        ];

        foreach ($vatRates as $vatRate) {
            VatRate::updateOrCreate(
                ['iso_code' => $vatRate['iso_code']],
                $vatRate
            );
        }
    }
}
