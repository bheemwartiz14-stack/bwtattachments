<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait ExtractsUserMeta
{
    private function extract(array &$data, string $companyField = 'wholesale_company_name'): array
    {
        $meta = [
            $companyField => $data[$companyField] ?? null,
            'vat_number' => $data['vat_number'] ?? null,
            'address' => $data['address'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? null,
            'website' => $data['website'] ?? null,
        ];
        $margin = $data['commission_percentage'] ?? 0;

        unset(
            $data[$companyField],
            $data['vat_number'],
            $data['address'],
            $data['postal_code'],
            $data['city'],
            $data['country'],
            $data['website'],
            $data['commission_percentage'],
        );

        return [$meta, $margin];
    }

    private function saveMeta(Model $user, array $meta): mixed
    {
        return $user->userMeta()->updateOrCreate(
            ['user_id' => $user->id],
            ['metadata' => array_merge($user->userMeta?->metadata ?? [], $meta)]
        );
    }

    private function saveMargin(Model $user, float $value, string $type = 'wholesale'): void
    {
        $user->userMargin()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => $type, 'margin_type' => 'percentage', 'margin_value' => $value]
        );
    }
}
