<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'quotation_number',
    'reference',
    'margin_percentage',
    'reseller_id',
    'delivery_country',
    'sub_total',
    'tax_amount',
    'margin_amount',
    'vat_percentage',
    'grand_total',
    'tax_rate',
    'valid_until',
    'issue_date',
    'status',
])]
class Quotation extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected function casts(): array
    {
        return [
            'margin_percentage' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'valid_until' => 'date',
            'issue_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
