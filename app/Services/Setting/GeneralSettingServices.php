<?php
declare(strict_types=1);

namespace App\Services\Setting;

use App\Models\SiteSetting;
use App\Traits\ResolvesTempFiles;

class GeneralSettingServices
{
    use ResolvesTempFiles;

    public function getAll(): array
    {
        return SiteSetting::pluck('value', 'key')->toArray();
    }

    public function getMediaUrl(string $key, string $collection): ?string
    {
        return SiteSetting::where('key', $key)
            ->first()
            ?->getFirstMediaUrl($collection);
    }

    public function getMediaId(string $key, string $collection): mixed
    {
        return SiteSetting::where('key', $key)
            ->first()
            ?->getFirstMedia($collection)
            ?->id;
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

    public function handleMediaUpload(array &$data, string $field, string $collection): void
    {
        $file = $this->resolveTempImage($data, $field);
        if ($file) {
            $setting = SiteSetting::firstOrCreate(['key' => $field]);
            $setting->addMedia($file)->toMediaCollection($collection);
        }
    }
}
