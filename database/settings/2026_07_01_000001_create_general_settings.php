<?php
declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_title', 'BWT Attachment Portal');
        $this->migrator->add('general.support_email', 'info@bwt-attachments.com');
        $this->migrator->add('general.support_phone', '+31 (0) 85 123 4567');
        $this->migrator->add('general.address_line_1', 'Industrieweg 12');
        $this->migrator->add('general.address_line_2', '');
        $this->migrator->add('general.city', 'Amsterdam');
        $this->migrator->add('general.state', 'North Holland');
        $this->migrator->add('general.pin_code', '1234 AB');
        $this->migrator->add('general.country', 'Netherlands');
        $this->migrator->add('general.logo_path', null);
        $this->migrator->add('general.logo_favicon', null);
    }

    public function down(): void
    {
        $this->migrator->delete('general');
    }
};
