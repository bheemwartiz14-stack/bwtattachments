<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
#[Fillable([
    'name',
    'username',
    'email',
    'phone',
    'password',
    'is_first_time',
    'parent_id',
])]
#[Hidden([
    'password',
    'remember_token'
])]
class User extends Authenticatable implements HasMedia
{
    use HasFactory;
    use HasRoles;
    use HasUuids;
    use Notifiable;
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
            ]);
        $this->addMediaCollection('image') ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])->singleFile();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'is_first_time' => 'boolean',
        ];
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

     public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function userMeta()
    {
        return $this->hasOne(UserMeta::class);
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrices::class);
    }

}