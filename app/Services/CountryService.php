<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use Rinvex\Country\CountryLoader;

class CountryService
{
    public function all(): Collection
    {
        return collect(CountryLoader::countries());
    }

    public function options(): array
    {
        return $this->all()
            ->mapWithKeys(fn ($country) => [
                $country['iso_3166_1_alpha2'] ?? '' => $country['name'] ?? '',
            ])
            ->filter(fn ($name, $code) => $code && $name)
            ->sort()
            ->toArray();
    }
}
