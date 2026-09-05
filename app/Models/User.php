<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Resources\QuotationProductResource;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Order;
use App\Models\UserMargin;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'username',
    'email',
    'phone',
    'password',
    'parent_id',
    'vat_id',
    'country',
    'country_code',
])]
#[Hidden([
    'password',
    'remember_token'
])]
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens;
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

        $this->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->singleFile();

        // Wholesale client logo - single JPG/JPEG/PNG/WebP
        $this->addMediaCollection('wholesale_client_logo')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/webp',
            ]);

        // Retailer client logo - single JPG/JPEG/PNG/WebP
        $this->addMediaCollection('retailer_client_logo')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/webp',
            ]);

        // Customer logo - single JPG/JPEG/PNG/WebP
        $this->addMediaCollection('customer_logo')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/webp',
            ]);
    }

    /**
     * Register media conversions.
     *
     * Logo conversion (500x500, Fit::Crop, quality 90) applies only to
     * wholesale_client_logo, retailer_client_logo and customer_logo collections.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Logo conversion - 500x500 Crop, quality 90, only for logo collections
        $this->addMediaConversion('logo')
            ->fit(Fit::Crop, 500, 500)
            ->quality(90)
            ->nonQueued()
            ->performOnCollections('wholesale_client_logo', 'retailer_client_logo', 'customer_logo');
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Examples
    |--------------------------------------------------------------------------
    |
    | // Wholesale client logo
    | $user->addMedia($request->file('wholesale_client_logo'))
    |     ->toMediaCollection('wholesale_client_logo');
    |
    | // Retailer client logo
    | $user->addMedia($request->file('retailer_client_logo'))
    |     ->toMediaCollection('retailer_client_logo');
    |
    | // Customer logo
    | $user->addMedia($request->file('customer_logo'))
    |     ->toMediaCollection('customer_logo');
    |
    | // With temp upload (Spatie pattern used in project)
    | // $user->addMedia($tempFile)->toMediaCollection('wholesale_client_logo');
    |
    |--------------------------------------------------------------------------
    | Retrieval Examples (converted logo URL - 500x500 Crop)
    |--------------------------------------------------------------------------
    |
    | $wholesaleLogoUrl = $user->getFirstMediaUrl('wholesale_client_logo', 'logo');
    | $retailerLogoUrl  = $user->getFirstMediaUrl('retailer_client_logo', 'logo');
    | $customerLogoUrl  = $user->getFirstMediaUrl('customer_logo', 'logo');
    |
    | // Fallback to original if conversion not ready
    | // $user->getFirstMediaUrl('wholesale_client_logo');
    |
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_from_user_id');
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

    public function userMargin()
    {
        return $this->hasOne(UserMargin::class);
    }
    public function productPrices()
    {
        return $this->hasMany(ProductPrices::class);
    }

    public function userProducts()
    {
        return $this->hasMany(UserProduct::class);
    }

    public function getQuotationProductIds(): array
    {
        return app(\App\Services\UserProductService::class)->getQuotationProductIds($this);
    }

    public function getQuotationProductList(): array
    {
        return QuotationProductResource::collection(
            $this->userProducts()
                ->where('is_quotation', true)
                ->with('product')
                ->get()
        )->resolve();
    }
}
