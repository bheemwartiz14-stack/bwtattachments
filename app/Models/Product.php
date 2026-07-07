<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
#[Fillable([
    'category_id',
    'subcategory_id',
    'connection_id',
    'product_code',
    'product_title',
    'product_description',
    'weight',
    'machine_weight',
    'machine_class',
    'hinges',
    'width',
    'volume',
    'thickness',
    'cutting_edge_thickness',
    'reach',
    'teeth',
    'stick_width',
    'pin_center',
    'pin_hole',
    'ddp_price',
    'internal_notes',
    'material',
    'status',
    'product_feature_image',
    'product_gallery_images',
    'product_pdf',
    'drawing_number',
])]
class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUuids;
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->singleFile();

        $this->addMediaCollection('gallery')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

        $this->addMediaCollection('pdfs')
            ->useDisk('local')
            ->acceptsMimeTypes(['application/pdf']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($media && !str_starts_with($media->mime_type, 'image/')) {
            return;
        }

        $this->addMediaConversion('thumb')
            ->crop(150, 100)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('small')
            ->crop(450, 300)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->crop(900, 600)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->crop(1500, 1000)
            ->nonQueued();
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function connection()
    {
        return $this->belongsTo(Connection::class);
    }

    public function quotationItems()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrices::class);
    }
}
