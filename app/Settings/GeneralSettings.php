<?php
declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_title = '';
    public string $support_email = '';
    public string $support_phone = '';

    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $city = '';
    public string $state = '';
    public string $pin_code = '';
    public string $country = '';

    public ?string $logo_path = null;
    public ?string $logo_favicon = null;

    public static function group(): string
    {
        return 'general';
    }
}
