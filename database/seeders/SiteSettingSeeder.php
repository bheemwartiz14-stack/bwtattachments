<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'site_title', 'value' => 'BIG Work Tools'],
            ['key' => 'site_address', 'value' => '123 Business Ave, Suite 100, New York, NY 10001, USA'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567'],
            ['key' => 'contact_email', 'value' => 'info@bigworktools.com'],
            ['key' => 'meta_description', 'value' => 'BIG Work Tools - Your trusted source for wholesale attachment products'],
            ['key' => 'meta_keywords', 'value' => 'BIG Work Tools, wholesale attachments, B2B, tools'],
            ['key' => 'footer_text', 'value' => '© All rights reserved | BWT Attachments'],
        ];

        foreach ($defaults as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
