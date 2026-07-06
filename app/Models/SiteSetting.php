<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SiteSetting extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('site_logo')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->singleFile();
    }
}
