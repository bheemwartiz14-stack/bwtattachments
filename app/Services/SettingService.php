<?php
declare(strict_types=1);

namespace App\Services;

use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\LaravelSettings\Migrations\SettingsMigrator;

class SettingService
{
    public function __construct(
        protected GeneralSettings $generalSettings,
    ) {}

    public function getAll(): GeneralSettings
    {
        return $this->generalSettings;
    }

    public function update(array $data): void
    {
        $this->ensureSettingsExist();

        foreach ($data as $key => $value) {
            if ($key === 'logo_path_temp') {
                $this->handleTempFile('logo_path', $value);
                continue;
            }
            if ($key === 'logo_favicon_temp') {
                $this->handleTempFile('logo_favicon', $value);
                continue;
            }
            $this->generalSettings->{$key} = $value ?? '';
        }
        $this->generalSettings->save();
    }

    private function ensureSettingsExist(): void
    {
        $migrator = app(SettingsMigrator::class);

        $properties = [
            'general.site_title',
            'general.support_email',
            'general.support_phone',
            'general.address_line_1',
            'general.address_line_2',
            'general.city',
            'general.state',
            'general.pin_code',
            'general.country',
            'general.logo_path',
            'general.logo_favicon',
        ];

        foreach ($properties as $property) {
            if (!$migrator->exists($property)) {
                $migrator->add($property, '');
            }
        }
    }

    private function handleTempFile(string $settingKey, ?string $jsonData): void
    {
        if (!$jsonData) return;

        $parsed = json_decode($jsonData, true);
        if (!$parsed || !isset($parsed['token'])) return;

        $disk = Storage::disk('public');
        $files = $disk->files('temp/' . $parsed['token']);
        if (empty($files)) return;

        $ext = $parsed['extension'] ?? 'webp';
        $filename = Str::uuid() . '.' . $ext;
        $permPath = "settings/{$filename}";

        $disk->move($files[0], $permPath);

        $disk->deleteDirectory('temp/' . $parsed['token']);

        $oldPath = $this->generalSettings->{$settingKey};
        if ($oldPath) {
            $oldDiskPath = str_replace('/storage/', '', $oldPath);
            $disk->delete($oldDiskPath);
        }

        $this->generalSettings->{$settingKey} = '/storage/' . $permPath;
    }
}
