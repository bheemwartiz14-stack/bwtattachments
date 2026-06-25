<?php

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
            ->useDisk('public')
            ->acceptsMimeTypes(['application/pdf']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);

        $this->addMediaConversion('small')
            ->width(480);

        $this->addMediaConversion('large')
            ->width(1200);
    }

    protected function casts(): array
    {
        return [
            'ddp_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'machine_weight' => 'decimal:2',
            'width' => 'decimal:2',
            'volume' => 'decimal:2',
            'cutting_edge_thickness' => 'decimal:2',
            'stick_width' => 'decimal:2',
            'pin_center' => 'decimal:2',
            'pin_hole' => 'decimal:2',
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
