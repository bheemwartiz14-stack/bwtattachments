<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'category_id',
    'subcategory_id',
    'connection_id',
    'product_code',
    'product_description',
    'weight',
    'machine_weight',
    'hinges',
    'width',
    'volume',
    'cutting_edge_thickness',
    'teeth',
    'stick_width',
    'pin_center',
    'pin_hole',
    'ddp_price',
    'pdf_file',
    'internal_notes',
    'status',
])]
class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
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

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function quotationItems()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
