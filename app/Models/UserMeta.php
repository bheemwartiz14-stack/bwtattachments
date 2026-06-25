<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class UserMeta extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('wholesale_client_logo') ->singleFile()  ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
