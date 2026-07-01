<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Settings\GeneralSettings;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $migration = require database_path('settings/2026_07_01_000001_create_general_settings.php');
        $migration->up();

        $settings = app(GeneralSettings::class);

        $settings->site_title = 'BWT Attachment Portal';
        $settings->support_email = 'info@bwt-attachments.com';
        $settings->support_phone = '+31 (0) 85 123 4567';

        $settings->address_line_1 = 'Industrieweg 12';
        $settings->address_line_2 = '';
        $settings->city = 'Amsterdam';
        $settings->state = 'North Holland';
        $settings->pin_code = '1234 AB';
        $settings->country = 'Netherlands';

        $settings->logo_path = null;
        $settings->logo_favicon = null;

        $settings->save();
    }
}