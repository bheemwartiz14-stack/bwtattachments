<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductPrices extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'base_price',
        'margin',
        'final_price',
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
