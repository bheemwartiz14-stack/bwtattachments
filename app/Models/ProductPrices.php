<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductPrices extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'user_id',
        'assigned_by',
        'type',
        'price',
        'margin',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'margin' => 'decimal:2',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
