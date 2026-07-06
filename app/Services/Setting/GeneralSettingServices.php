<?php
declare(strict_types=1);

namespace App\Services\Setting;

use App\Models\SiteSetting;

class GeneralSettingServices
{
    public function getAll(): array
    {
        return SiteSetting::pluck('value', 'key')->toArray();
    }

    public function saveTextSettings(array $data): void
    {
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }
    }
}
